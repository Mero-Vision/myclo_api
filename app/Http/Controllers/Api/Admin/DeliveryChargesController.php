<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryChargeResource;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryChargesController extends Controller
{
    public function index()
    {

        $deliveryCharges = DeliveryCharge::get();

        return DeliveryChargeResource::collection($deliveryCharges);
    }

    public function bulkUpdate(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'districts' => 'required|array',
        //     'districts.*.id' => 'nullable|exists:delivery_charges,id',
        //     'districts.*.cost_0_1kg' => 'nullable|numeric|min:0',
        //     'districts.*.cost_1_2kg' => 'nullable|numeric|min:0',
        //     'districts.*.cost_2_3kg' => 'nullable|numeric|min:0',
        //     'districts.*.cost_3_5kg' => 'nullable|numeric|min:0',
        //     'districts.*.cost_5_10kg' => 'nullable|numeric|min:0',
        //     'districts.*.cost_above_10kg' => 'nullable|numeric|min:0',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors(),
        //     ], 422);
        // }

        try {
            foreach ($request->districts as $districtData) {

                if (!isset($districtData['id'])) {
                    continue;
                }

                $deliveryCharge = DeliveryCharge::find($districtData['id']);

                if ($deliveryCharge) {
                    $deliveryCharge->update([
                        'cost_0_1kg' => $districtData['cost_0_1kg'] ?? $deliveryCharge->cost_0_1kg,
                        'cost_1_2kg' => $districtData['cost_1_2kg'] ?? $deliveryCharge->cost_1_2kg,
                        'cost_2_3kg' => $districtData['cost_2_3kg'] ?? $deliveryCharge->cost_2_3kg,
                        'cost_3_5kg' => $districtData['cost_3_5kg'] ?? $deliveryCharge->cost_3_5kg,
                        'cost_5_10kg' => $districtData['cost_5_10kg'] ?? $deliveryCharge->cost_5_10kg,
                        'cost_above_10kg' => $districtData['cost_above_10kg'] ?? $deliveryCharge->cost_above_10kg,
                        'cash_on_delivery' => $districtData['cash_on_delivery'] ?? $deliveryCharge->cash_on_delivery,
                    ]);
                }
            }

            return responseSuccess('Delivery charges updated successfully for all districts');
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}
