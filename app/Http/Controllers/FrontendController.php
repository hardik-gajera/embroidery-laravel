<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Design;
use App\Models\DesignPackage;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function home()
    {
        $parentCategories = Category::parents()->withCount('children')->get();
        $featuredDesigns = Design::latest()->take(8)->get();
        $packages = DesignPackage::where('state', 'confirm')->latest()->take(4)->get();
        return view('frontend.home', compact('parentCategories', 'featuredDesigns', 'packages'));
    }

    public function packages()
    {
        $packages = DesignPackage::where('state', 'confirm')->get();
        return view('frontend.packages', compact('packages'));
    }

    public function packageDetail($id)
    {
        $package = DesignPackage::findOrFail($id);
        return view('frontend.package-detail', compact('package'));
    }

    public function buyPackage(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $package = DesignPackage::findOrFail($request->package_id);
        $customer = Customer::find(session('customer_id'));
        $razorpayKey = config('services.razorpay.key');
        $amount = $package->price * 100;

        return view('frontend.package-payment', compact('package', 'customer', 'razorpayKey', 'amount'));
    }

    public function packagePaymentSuccess(Request $request)
    {
        $package = DesignPackage::findOrFail($request->package_id);
        $customer = Customer::find(session('customer_id'));

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addMonths($package->time_period);

        $customer->update([
            'package_id' => $package->id,
            'package_start_date' => $startDate,
            'package_end_date' => $endDate,
            'total_design' => $package->number_of_design,
            'downloaded_design' => 0,
        ]);

        Order::create([
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'amount' => $request->amount / 100,
            'razorpay_order_id' => $request->razorpay_order_id ?? '',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid',
        ]);

        return redirect('/')->with('success', 'Package purchased successfully!');
    }

    public function categories($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $children = $category->children;

        if ($children->isEmpty()) {
            $designs = Design::where('category_id', $id)->paginate(12);
            return view('frontend.designs', compact('category', 'designs'));
        }

        $designs = Design::where('category_id', $id)->get();
        return view('frontend.categories', compact('category', 'children', 'designs'));
    }

    public function designs($id)
    {
        $category = Category::findOrFail($id);
        $designs = Design::where('category_id', $id)->paginate(12);
        return view('frontend.designs', compact('category', 'designs'));
    }

    public function designDetail($id)
    {
        $design = Design::with('category')->findOrFail($id);
        $related = Design::where('category_id', $design->category_id)->where('id', '!=', $id)->take(4)->get();
        return view('frontend.design-detail', compact('design', 'related'));
    }

    public function showLogin()
    {
        return view('frontend.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        $customer = Customer::where('email', $request->email)->first();

        if ($customer && Hash::check($request->password, $customer->password)) {
            session(['customer_id' => $customer->id, 'customer_name' => $customer->name]);
            session(['cart_count' => Cart::where('customer_id', $customer->id)->count()]);
            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'mobile_no' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Customer::create($request->only('name', 'email', 'mobile_no', 'password'));
        session(['customer_id' => $customer->id, 'customer_name' => $customer->name, 'cart_count' => 0]);

        return redirect()->intended('/')->with('success', 'Account created successfully!');
    }

    public function logout()
    {
        session()->forget(['customer_id', 'customer_name', 'cart_count']);
        return redirect('/')->with('success', 'Logged out.');
    }

    public function cart()
    {
        $cartItems = Cart::with('design')->where('customer_id', session('customer_id'))->get();
        $total = $cartItems->sum(fn($item) => $item->design->design_price);
        return view('frontend.cart', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login')->with('error', 'Please login first.');
        }

        Cart::firstOrCreate([
            'customer_id' => session('customer_id'),
            'design_id' => $request->design_id,
        ]);

        session(['cart_count' => Cart::where('customer_id', session('customer_id'))->count()]);

        return back()->with('success', 'Added to cart!');
    }

    public function removeFromCart($id)
    {
        Cart::where('id', $id)->where('customer_id', session('customer_id'))->delete();
        session(['cart_count' => Cart::where('customer_id', session('customer_id'))->count()]);
        return back()->with('success', 'Removed from cart.');
    }

    public function buyNow(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $design = Design::findOrFail($request->design_id);
        $customer = Customer::find(session('customer_id'));

        // Check if already purchased
        $alreadyPurchased = Order::where('customer_id', $customer->id)
            ->where('design_id', $design->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyPurchased) {
            return redirect()->route('frontend.my-designs')->with('success', 'You already own this design. Download it from here.');
        }

        // Check active package
        if ($customer->hasActivePackage()) {
            if ($customer->downloaded_design < $customer->total_design) {
                // Free download via package
                return view('frontend.package-download', compact('design', 'customer'));
            } else {
                // Package limit exceeded
                $razorpayKey = config('services.razorpay.key');
                $amount = $design->design_price * 100;
                $packageExceeded = true;
                return view('frontend.payment', compact('design', 'customer', 'razorpayKey', 'amount', 'packageExceeded'));
            }
        }

        // No package — go to payment
        $razorpayKey = config('services.razorpay.key');
        $amount = $design->design_price * 100;
        $packageExceeded = false;

        return view('frontend.payment', compact('design', 'customer', 'razorpayKey', 'amount', 'packageExceeded'));
    }

    public function claimDesign(Request $request)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $customer = Customer::find(session('customer_id'));
        $design = Design::findOrFail($request->design_id);

        if (!$customer->hasActivePackage() || $customer->downloaded_design >= $customer->total_design) {
            return back()->with('error', 'Package limit exceeded.');
        }

        // Create order with 0 amount (package claim)
        Order::create([
            'customer_id' => $customer->id,
            'design_id' => $design->id,
            'amount' => 0,
            'razorpay_payment_id' => 'package_claim',
            'status' => 'paid',
        ]);

        $customer->increment('downloaded_design');

        Cart::where('customer_id', $customer->id)->where('design_id', $design->id)->delete();
        session(['cart_count' => Cart::where('customer_id', $customer->id)->count()]);

        return view('frontend.purchase-success', compact('design'));
    }

    public function paymentSuccess(Request $request)
    {
        Order::create([
            'customer_id' => session('customer_id'),
            'design_id' => $request->design_id,
            'amount' => $request->amount / 100,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'status' => 'paid',
        ]);

        Cart::where('customer_id', session('customer_id'))->where('design_id', $request->design_id)->delete();
        session(['cart_count' => Cart::where('customer_id', session('customer_id'))->count()]);

        $design = Design::findOrFail($request->design_id);
        return view('frontend.purchase-success', compact('design'));
    }

    public function myDesigns()
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $orders = Order::with('design')
            ->where('customer_id', session('customer_id'))
            ->whereNotNull('design_id')
            ->latest()
            ->get();

        return view('frontend.my-designs', compact('orders'));
    }

    public function myPackages()
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $customer = Customer::with('package')->find(session('customer_id'));
        $packageOrders = Order::with('package')
            ->where('customer_id', session('customer_id'))
            ->whereNotNull('package_id')
            ->latest()
            ->get();

        return view('frontend.my-packages', compact('customer', 'packageOrders'));
    }

    public function downloadDesign($id)
    {
        if (!session('customer_id')) {
            return redirect()->route('frontend.login');
        }

        $design = Design::findOrFail($id);
        $customer = Customer::find(session('customer_id'));

        // Check if customer purchased this design or has active package
        $hasPurchased = Order::where('customer_id', $customer->id)
            ->where('design_id', $id)
            ->where('status', 'paid')
            ->exists();

        $hasActivePackage = $customer->hasActivePackage() && $customer->downloaded_design < $customer->total_design;

        if (!$hasPurchased && !$hasActivePackage) {
            return back()->with('error', 'You do not have access to download this design.');
        }

        $filePath = storage_path('app/public/' . $design->emb_file);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Design file not available.');
        }

        // If downloading via package (not individually purchased), increment counter
        if (!$hasPurchased && $hasActivePackage) {
            $customer->increment('downloaded_design');
        }

        $extension = pathinfo($design->file_name, PATHINFO_EXTENSION) ?: $design->design_format;
        $fileName = ($design->design_code ?? $design->name) . '.' . $extension;

        return response()->file($filePath, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
