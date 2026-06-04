<?php

namespace App\Http\Controllers;

use App\Models\DesignPackage;
use App\Http\Requests\StoreDesignPackageRequest;
use App\Http\Requests\UpdateDesignPackageRequest;
use Illuminate\Support\Facades\Storage;

class DesignPackageController extends Controller
{
    public function index()
    {
        $query = DesignPackage::query();

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($state = request('state')) {
            $query->where('state', $state);
        }

        $packages = $query->latest()->paginate(10)->withQueryString();
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(StoreDesignPackageRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('package_img')) {
            $data['package_img'] = $request->file('package_img')->store('packages', 'public');
        }

        DesignPackage::create($data);
        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    public function show(DesignPackage $package)
    {
        return view('packages.show', compact('package'));
    }

    public function edit(DesignPackage $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(UpdateDesignPackageRequest $request, DesignPackage $package)
    {
        $data = $request->validated();

        if ($request->hasFile('package_img')) {
            if ($package->package_img) {
                Storage::disk('public')->delete($package->package_img);
            }
            $data['package_img'] = $request->file('package_img')->store('packages', 'public');
        }

        $package->update($data);
        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(DesignPackage $package)
    {
        if ($package->package_img) {
            Storage::disk('public')->delete($package->package_img);
        }
        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
