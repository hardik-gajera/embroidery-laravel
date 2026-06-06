<?php

namespace App\Http\Controllers\Api;

use App\Models\Design;
use App\Models\Order;
use Illuminate\Http\Request;

class DesignController extends BaseApiController
{
    public function index(Request $request)
    {
        try {
            $query = Design::with('category');
            
            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('design_code', 'like', '%' . $request->search . '%');
            }
            
            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }
            
            $designs = $query->paginate(12);
            
            return $this->successResponse('Designs retrieved successfully', $designs->toArray());
        } catch (\Exception $e) {
            return $this->handleException($e, 'Get Designs');
        }
    }

    public function show($id)
    {
        try {
            $design = Design::with('category')->find($id);
            
            if (!$design) {
                return $this->errorResponse('Design not found', [], 404);
            }
            
            $related = Design::where('category_id', $design->category_id)
                             ->where('id', '!=', $id)
                             ->take(4)
                             ->get();
            
            return $this->successResponse('Design retrieved successfully', [
                'design' => $design,
                'related_designs' => $related
            ]);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Get Design');
        }
    }

    public function featured()
    {
        try {
            $designs = Design::latest()->take(8)->get();
            
            return $this->successResponse('Featured designs retrieved successfully', $designs->toArray());
        } catch (\Exception $e) {
            return $this->handleException($e, 'Get Featured Designs');
        }
    }

    public function download(Request $request, $id)
    {
        try {
            $design = Design::find($id);
            
            if (!$design) {
                return $this->errorResponse('Design not found', [], 404);
            }
            
            $customer = $request->user();

            $hasPurchased = Order::where('customer_id', $customer->id)
                ->where('design_id', $id)
                ->where('status', 'paid')
                ->exists();

            $hasActivePackage = $customer->hasActivePackage() && 
                               $customer->downloaded_design < $customer->total_design;

            if (!$hasPurchased && !$hasActivePackage) {
                return $this->errorResponse('You do not have access to download this design', [], 403);
            }

            $filePath = storage_path('app/public/' . $design->emb_file);

            if (!file_exists($filePath)) {
                return $this->errorResponse('Design file not available', [], 404);
            }

            if (!$hasPurchased && $hasActivePackage) {
                $customer->increment('downloaded_design');
            }

            $extension = pathinfo($design->file_name, PATHINFO_EXTENSION) ?: $design->design_format;
            $fileName = ($design->design_code ?? $design->name) . '.' . $extension;

            return response()->download($filePath, $fileName);
        } catch (\Exception $e) {
            return $this->handleException($e, 'Download Design');
        }
    }
}