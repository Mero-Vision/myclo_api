<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {

        $email = $request->email;
        $password = $request->password;

        try {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $user = Auth()->user();
                if ($user->hasRole(User::CUSTOMER)) {
                    if ($user->email_verified_at == null) {
                        return responseError('The email is not verified!', 500);
                    }

                    $token = $user->createToken('login_token')->accessToken;


                    $data = [
                        'user' => new UserResource($user),
                        'token' => $token,

                    ];

                    $ipAddress = $request->header('X-Forwarded-For') ?: $request->ip();

                    if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        $ipv4Address = $ipAddress;
                    } else {
                        $ipv4Address = null;
                    }

                    $response = Http::get("http://ip-api.com/json/{$ipv4Address}?fields=city,region,country");

                    if ($response->successful()) {
                        $locationData = $response->json(); // Get the data as an array


                        $city = $locationData['city'] ?? 'Unknown';
                        $region = $locationData['region'] ?? 'Unknown';
                        $country = $locationData['country'] ?? 'Unknown';
                    } else {

                        $city = $region = $country = 'Unknown';
                    }
                    $agent = new Agent();
                    LoginActivity::create([
                        'user_id' => $user->id,
                        'login_user_name' => Auth::user()->name,
                        'ip_address' => $ipv4Address,
                        'device' => $agent->device(),
                        'browser' => $agent->browser(),
                        'location' => "{$city}, {$region}",
                        'country' => $country,
                    ]);
                    return responseSuccess($data, 200, 'Login success!');
                } else {
                    return responseError('The credentials does not match with our record!', 500);
                }
            } else {
                return responseError('The credentials does not match with our record!', 500);
            }
        } catch (\Throwable $th) {
            return responseError($th->getMessage(), 500);
        }
    }
}
