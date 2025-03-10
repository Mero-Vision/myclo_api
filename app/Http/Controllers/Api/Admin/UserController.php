<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function show($id)
    {

        $user = User::find($id);
        if (!$user) {
            return responseError("User ID Not Found", 500);
        }

        return new UserResource($user);
    }

    public function profileImageUpdate(Request $request, string $id)
    {
        
        $user = User::role(User::ADMIN)->find($id);
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
            return responseSuccess(new UserResource($user), 200, 'Profile image updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return responseError('Failed to update profile image. Please try again.', 500);
        }
    }


    public function changePassword(ChangePasswordRequest $request)
    {

        try {
            $user = auth()->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return responseError("Current Password Does Not Matched!", 500);
            } elseif ($request->password == $request->current_password) {
                return responseError("New password must differ from current password!", 500);
            } else {

                $user->password = Hash::make($request->password);
                $user->save();
                return responseSuccess(new UserResource($user), 200, 'Password is changed successfully!');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }

   
}
