<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index(Request $request)
    {
        $query = Design::with('category');
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('design_code', 'like', '%' . $request->search . '%');
        }
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $designs = $query->paginate(12);
        
        return response()->json([
            'success' => true,
            'data' => $designs
        ]);
    }

    public function show($id)
    {
        $design = Design::with('category')->findOrFail($id);
        $related = Design::where('category_id', $design->category_id)
                         ->where('id', '!=', $id)
                         ->take(4)
                         ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'design' => $design,
                'related_designs' => $related
            ]
        ]);
    }

    public function featured()
    {
        $designs = Design::latest()->take(8)->get();
        
        return response()->json([
            'success' => true,
            'data' => $designs
        ]);
    }

    public function download(Request $request, $id)
    {
        $design = Design::findOrFail($id);
        $customer = $request->user();

        $hasPurchased = Order::where('customer_id', $customer->id)
            ->where('design_id', $id)
            ->where('status', 'paid')
            ->exists();

        $hasActivePackage = $customer->hasActivePackage() && 
                           $customer->downloaded_design < $customer->total_design;

        if (!$hasPurchased && !$hasActivePackage) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to download this design.'
            ], 403);
        }

        $filePath = storage_path('app/public/' . $design->emb_file);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'Design file not available.'
            ], 404);
        }

        if (!$hasPurchased && $hasActivePackage) {
            $customer->increment('downloaded_design');
        }

        $extension = pathinfo($design->file_name, PATHINFO_EXTENSION) ?: $design->design_format;
        $fileName = ($design->design_code ?? $design->name) . '.' . $extension;

        return response()->download($filePath, $fileName);
    }
}