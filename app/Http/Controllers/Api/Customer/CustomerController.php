<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Resources\CustomerDashboardResource;
use App\Http\Resources\CustomerResource;
use App\Mail\CustomerSignUpMail;
use App\Models\AccountVerificationToken;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function profileImageUpdate(Request $request, string $id)
    {
        
        $user = User::role(User::CUSTOMER)->find($id);
        if (!$user) {
            return responseError("User not found.", 404);
        }

       
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust mime types and size as needed
        ]);

        try {
           
            DB::beginTransaction();

           
            if ($request->hasFile('profile_image')) {
                $user->clearMediaCollection('profile_image');
                $user->addMedia($request->file('profile_image'))->toMediaCollection('profile_image');
            }

           
            DB::commit();

           
            return responseSuccess(new CustomerResource($user), 200, 'Profile image updated successfully!');
        } catch (\Exception $e) {
            
            DB::rollBack();

           

            
            return responseError('Failed to update profile image. Please try again.', 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $customer = DB::transaction(function () use ($request) {

                $customer = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password
                ]);

                $customer->assignRole(User::CUSTOMER);
                // $token = Str::random(60);

                // AccountVerificationToken::where('email', $customer->email)->delete();

                // AccountVerificationToken::create([
                //     'email' => $customer->email,
                //     'token' => $token,
                //     'expire_at' => Carbon::now()->addMinutes(30),
                // ]);

                // Mail::to($request->email)->send(new CustomerSignUpMail($customer, $token));

                return $customer;
            });
            if ($customer) {
                return responseSuccess(new CustomerResource($customer), 200, 'Verification Mail Has Been Send Successfully!');
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
        $user = User::role(User::CUSTOMER)->withCount('orders','carts','wishlists')->find($id);
        if (!$user) {
            return responseError("Customer ID Not Found", 500);
        }

        return new CustomerDashboardResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, string $id)
    {
        $customer = User::role(User::CUSTOMER)->find($id);
        if (!$customer) {
            return responseError("Customer ID Not Found", 500);
        }

        try {
            $customer = DB::transaction(function () use ($request,$customer) {

                $customer->update([
                        'name'=>$request->name
                ]);

                return $customer;
            });
            if ($customer) {
                return responseSuccess(new CustomerResource($customer), 200, 'Detail Updated Successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = Auth::user();
            $updatedUser = $this->userService->changePassword($request, $user);

            return responseSuccess($updatedUser, 200, 'Password changed successfully!');
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}