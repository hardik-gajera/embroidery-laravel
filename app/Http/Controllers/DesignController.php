<?php

namespace App\Http\Controllers;

use App\Models\Design;
use App\Models\Category;
use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index()
    {
        $query = Design::with('category');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('design_code', 'like', "%{$search}%");
            });
        }

        if ($categoryId = request('category')) {
            $query->where('category_id', $categoryId);
        }

        $designs = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('designs.index', compact('designs', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('designs.create', compact('categories'));
    }

    public function store(StoreDesignRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('emb_file');
        $data['emb_file'] = $file->store('designs/files', 'public');
        $data['file_name'] = $file->getClientOriginalName();
        $data['design_format'] = $file->getClientOriginalExtension();

        if ($request->hasFile('design_img')) {
            $data['design_img'] = $request->file('design_img')->store('designs/images', 'public');
        }

        Design::create($data);
        return redirect()->route('designs.index')->with('success', 'Design created successfully.');
    }

    public function show(Design $design)
    {
        return view('designs.show', compact('design'));
    }

    public function edit(Design $design)
    {
        $categories = Category::all();
        return view('designs.edit', compact('design', 'categories'));
    }

    public function update(UpdateDesignRequest $request, Design $design)
    {
        $data = $request->validated();

        if ($request->hasFile('emb_file')) {
            Storage::disk('public')->delete($design->emb_file);
            $file = $request->file('emb_file');
            $data['emb_file'] = $file->store('designs/files', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['design_format'] = $file->getClientOriginalExtension();
        } else {
            unset($data['emb_file']);
        }

        if ($request->hasFile('design_img')) {
            if ($design->design_img) {
                Storage::disk('public')->delete($design->design_img);
            }
            $data['design_img'] = $request->file('design_img')->store('designs/images', 'public');
        }

        $design->update($data);
        return redirect()->route('designs.index')->with('success', 'Design updated successfully.');
    }

    public function destroy(Design $design)
    {
        Storage::disk('public')->delete($design->emb_file);
        if ($design->design_img) {
            Storage::disk('public')->delete($design->design_img);
        }
        $design->delete();
        return redirect()->route('designs.index')->with('success', 'Design deleted successfully.');
    }
}
