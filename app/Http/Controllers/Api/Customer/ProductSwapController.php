<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSWap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductSwapController extends Controller
{

    public function getRequesterSwaps()
    {
        $swaps = ProductSwap::with(['owner', 'requesterProduct', 'ownerProduct'])
            ->where('requester_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return responseSuccess($swaps);
    }

    /**
     * Get the list of swap requests where the user is the owner.
     */
    public function getOwnerSwaps()
    {
        $swaps = ProductSwap::with(['requester', 'requesterProduct', 'ownerProduct'])
            ->where('owner_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return responseSuccess($swaps);
    }

    public function requestSwap(Request $request)
    {
        $request->validate([
            'requester_product_id' => 'required|exists:products,id',
            'owner_product_id' => 'required|exists:products,id',
        ]);

        $ownerProduct = Product::find($request->owner_product_id);

        try {

            $productSwap = DB::transaction(function () use ($ownerProduct, $request) {

                $productSwap = ProductSwap::create([
                    'requester_id' => Auth::id(),
                    'owner_id' => $ownerProduct->client_id,
                    'requester_product_id' => $request->requester_product_id,
                    'owner_product_id' => $request->owner_product_id,
                    'swap_status' => 'pending',
                ]);

                return $productSwap;
            });
            if ($productSwap) {
                return responseSuccess($productSwap, 200, 'Swap request sent successfully.');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
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

        return responseSuccess($swap, 200, 'Swap accepted and products exchanged.');
    }

    public function rejectSwap($id)
    {
        $swap = ProductSWap::findOrFail($id);
        if ($swap->owner_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $swap->update(['swap_status' => 'rejected']);

        return responseSuccess($swap, 200, 'Swap request rejected.');
    }
}