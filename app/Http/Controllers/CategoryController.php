<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $query = Category::with('parent', 'children');

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (request('parent') === 'only') {
            $query->parents();
        } elseif ($parentId = request('parent')) {
            $query->where('parent_id', $parentId);
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        $parentCategories = Category::parents()->with('children')->get();

        return view('categories.index', compact('categories', 'parentCategories'));
    }

    public function create()
    {
        $parentCategories = Category::parents()->get();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::parents()->where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // If parent category, move children to selected parent or set null
        if ($category->children->count() > 0) {
            $moveTo = request('move_children_to');
            if ($moveTo) {
                $category->children()->update(['parent_id' => $moveTo]);
            } else {
                $category->children()->update(['parent_id' => null]);
            }
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Also delete images of children if deleting with children
        if (request('delete_children')) {
            foreach ($category->children as $child) {
                if ($child->image) {
                    Storage::disk('public')->delete($child->image);
                }
                $child->delete();
            }
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
