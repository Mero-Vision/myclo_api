<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingDetail\ShippingDetailStoreRequest;
use App\Http\Resources\ShippingDetailResource;
use App\Models\ShippingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShippingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagination_limit = request()->query('pagination_limit');

        $shippingDetails = ShippingDetail::where('user_id', Auth::user()->id)->latest();

        $pagination = $pagination_limit ? $shippingDetails->paginate($pagination_limit) : $shippingDetails->get();

        return ShippingDetailResource::collection($pagination);
    }


    public function updateStatus(ShippingDetailStoreRequest $request,$id)
    {

        $shippingDetail = ShippingDetail::find($id);
        if (!$shippingDetail) {
            return responseError('Shipping Detail Not Found!', 404);
        }

        try {
            $menuItem = DB::transaction(function () use ($request,$shippingDetail) {

                if ($request->status == "true") {

                    $shippingDetail->update([
                        'is_default' => "true"
                    ]);
                } elseif ($request->status == "false") {

                    $shippingDetail->update([
                        'is_default' => "false"
                    ]);
                }
            });

            return responseSuccess($shippingDetail, 201, 'Menu Item Status Updated Successfully!');
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $existingShippingDetails = ShippingDetail::where('user_id', Auth::user()->id)->get();
        if ($existingShippingDetails->isNotEmpty()) {
            $existingShippingDetails->each(function ($shippingDetail) {
                $shippingDetail->update([
                    'is_default' => 'false'
                ]);
            });
        }
        try {
            $shippingDetails = DB::transaction(function () use ($request) {
                $shippingDetails = ShippingDetail::create([
                    'user_id' => Auth::user()->id,
                    'recipient_name' => $request->recipient_name,
                    'contact_no' => $request->contact_no,
                    'email' => $request->email,
                    'region' => $request->region,
                    'district_city' => $request->district_city,
                    'address' => $request->address,
                    'landmark' => $request->landmark,
                ]);

                return $shippingDetails;
            });
            if ($shippingDetails) {
                return responseSuccess($shippingDetails, 200, 'Shipping Details Has Been Created Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shippingDetail = ShippingDetail::findOrFail($id);

        return new ShippingDetailResource($shippingDetail);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shippingDetail = ShippingDetail::find($id);
        if (!$shippingDetail) {
            return responseError('Shipping Detail Not Found', 500);
        }
        try {
            $shippingDetail = DB::transaction(function () use ($shippingDetail,$request) {
                
                $shippingDetail->update([
                    'recipient_name' => $request->recipient_name,
                    'contact_no' => $request->contact_no,
                    'email' => $request->email,
                    'region' => $request->region,
                    'district_city' => $request->district_city,
                    'address' => $request->address,
                    'landmark' => $request->landmark,
                ]);

                return $shippingDetail;
            });

            if ($shippingDetail) {
                return responseSuccess($shippingDetail, 200, 'Shipping Detail Updated Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $shippingDetail = ShippingDetail::find($id);
        if (!$shippingDetail) {
            return responseError('Shipping Detail Not Found', 500);
        }
        try {
            $shippingDetail = DB::transaction(function () use ($shippingDetail) {
                
                $shippingDetail->delete();

                return $shippingDetail;
            });

            if ($shippingDetail) {
                return responseSuccess($shippingDetail, 200, 'Shipping Detail Deleted Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}
