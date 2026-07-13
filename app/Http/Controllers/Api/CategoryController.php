<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 12);
        $categories = Category::parents()->withCount('children')->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::with('children')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'has_children' => $category->children->isNotEmpty()
            ]
        ]);
    }

    public function designs(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $perPage = $request->input('per_page', 12);
        $designs = Design::where('category_id', $id)->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'designs' => $designs
            ]
        ]);
    }
}