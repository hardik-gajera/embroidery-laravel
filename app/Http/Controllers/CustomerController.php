<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPackagePurchase;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $query = Customer::with('package');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::create($request->validated());
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load('package');
        $packageOrders = $this->getPackageHistory($customer);
        $packages = \App\Models\DesignPackage::whereIn('state', ['confirm', 'draft'])->get();
        return view('customers.show', compact('customer', 'packageOrders', 'packages'));
    }

    public function edit(Customer $customer)
    {
        $packageOrders = $this->getPackageHistory($customer);
        $packages = \App\Models\DesignPackage::whereIn('state', ['confirm', 'draft'])->get();
        return view('customers.edit', compact('customer', 'packageOrders', 'packages'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $customer->update($data);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function assignPackage(Request $request, Customer $customer)
    {
        $request->validate([
            'package_id' => 'required|exists:design_packages,id',
            'start_date' => 'required|date',
            'downloaded_design' => 'required|integer|min:0',
        ]);

        $package = \App\Models\DesignPackage::findOrFail($request->package_id);
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->addMonths($package->time_period);

        $customer->update([
            'package_id' => $package->id,
            'package_start_date' => $startDate,
            'package_end_date' => $endDate,
            'total_design' => $package->number_of_design,
            'downloaded_design' => $request->downloaded_design,
        ]);

        return redirect()->route('customers.show', $customer)->with('success', 'Package assigned successfully.');
    }

    public function removePackage(Customer $customer)
    {
        $customer->update([
            'package_id' => null,
            'package_start_date' => null,
            'package_end_date' => null,
            'total_design' => 0,
            'downloaded_design' => 0,
        ]);

        return redirect()->route('customers.show', $customer)->with('success', 'Package removed successfully.');
    }

    public function updatePackage(Request $request, Customer $customer)
    {
        $request->validate([
            'downloaded_design' => 'required|integer|min:0|max:' . $customer->total_design,
        ]);

        $customer->update([
            'downloaded_design' => $request->downloaded_design,
        ]);

        return redirect()->back()->with('success', 'Downloaded designs count updated successfully.');
    }

    public function addDownload(Request $request, Customer $customer)
    {
        $request->validate(['count' => 'required|integer|min:1']);

        $remaining = $customer->total_design - $customer->downloaded_design;
        $count = min($request->count, $remaining);

        if ($count <= 0) {
            return back()->with('error', 'No downloads remaining in this package.');
        }

        $customer->increment('downloaded_design', $count);

        return redirect()->route('customers.show', $customer)->with('success', "$count offline download(s) added successfully.");
    }

    private function getPackageHistory(Customer $customer)
    {
        // Get from orders table (new purchases via Laravel)
        $orders = \App\Models\Order::with('package')
            ->where('customer_id', $customer->id)
            ->whereNotNull('package_id')
            ->latest()
            ->get();

        // Get from customer_package_purchases (migrated from Odoo)
        $purchases = CustomerPackagePurchase::with('package')
            ->where('customer_id', $customer->id)
            ->latest('start_date')
            ->get();

        // Merge both into a unified collection
        $merged = $orders->map(function ($order) {
            return (object) [
                'id' => $order->id,
                'package' => $order->package,
                'total' => $order->package->number_of_design ?? 0,
                'downloaded' => 0,
                'start_date' => $order->created_at,
                'end_date' => $order->created_at?->addMonths($order->package->time_period ?? 0),
                'amount' => $order->amount,
                'source' => 'order',
            ];
        })->concat($purchases->map(function ($p) {
            return (object) [
                'id' => $p->id,
                'package' => $p->package,
                'total' => $p->total,
                'downloaded' => $p->downloaded,
                'start_date' => $p->start_date,
                'end_date' => $p->end_date,
                'amount' => $p->package->price ?? 0,
                'source' => 'odoo',
            ];
        }))->sortByDesc('start_date')->values();

        return $merged;
    }
}
