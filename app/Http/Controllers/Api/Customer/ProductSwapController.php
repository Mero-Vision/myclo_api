<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSWap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductSwapController extends Controller
{
    public function requestSwap(Request $request)
    {
        $request->validate([
            'requester_product_id' => 'required|exists:products,id',
            'owner_product_id' => 'required|exists:products,id',
        ]);

        $ownerProduct = Product::find($request->owner_product_id);

        $productSwap=ProductSwap::create([
            'requester_id' => Auth::id(),
            'owner_id' => $ownerProduct->client_id,
            'requester_product_id' => $request->requester_product_id,
            'owner_product_id' => $request->owner_product_id,
            'swap_status' => 'pending',
        ]);

        return responseSuccess($productSwap,200,'Swap request sent successfully.');
    }

    public function acceptSwap($id)
    {
        $swap = ProductSwap::findOrFail($id);
        if ($swap->owner_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        // Swap product ownership
        $requesterProduct = $swap->requesterProduct;
        $ownerProduct = $swap->ownerProduct;

        $tempOwner = $requesterProduct->client_id;
        $requesterProduct->client_id = $ownerProduct->client_id;
        $ownerProduct->client_id = $tempOwner;

        $requesterProduct->save();
        $ownerProduct->save();

        $swap->update(['swap_status' => 'accepted']);

        return responseSuccess($swap,200,'Swap accepted and products exchanged.');
    }

    public function rejectSwap($id)
    {
        $swap = ProductSWap::findOrFail($id);
        if ($swap->owner_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $swap->update(['swap_status' => 'rejected']);

        return responseSuccess($swap,200,'Swap request rejected.');
    }
}