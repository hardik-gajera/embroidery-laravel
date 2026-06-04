<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::parents()->withCount('children')->get();
        
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

    public function designs($id)
    {
        $category = Category::findOrFail($id);
        $designs = Design::where('category_id', $id)->paginate(12);
        
        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'designs' => $designs
            ]
        ]);
    }
}