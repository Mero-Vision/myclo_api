<?php
namespace App\Services;

use App\Http\Requests\User\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class UserService
{
    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        // Rate limiting to prevent brute force attacks
        $this->ensureIsNotRateLimited($request, $user);

        // Validate current password
        if (!Hash::check($request->current_password, $user->password)) {
            
            RateLimiter::hit($this->throttleKey($request, $user)); // Increment rate limiter
            throw new \Exception("Current password does not match!");
        }

        // Ensure new password is not the same as the current password
        if ($request->password === $request->current_password) {
            throw new \Exception("New password must differ from the current password!");
        }

        // Enforce password complexity (optional)
        $this->validatePasswordComplexity($request->password);

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear rate limiter on successful password change
        RateLimiter::clear($this->throttleKey($request, $user));

        return new UserResource($user);
    }

    /**
     * Ensure the request is not rate-limited.
     */
    protected function ensureIsNotRateLimited(ChangePasswordRequest $request, User $user)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request, $user), 5)) { // Allow 5 attempts per minute
            $seconds = RateLimiter::availableIn($this->throttleKey($request, $user));
            throw new \Exception("Too many attempts. Please try again in {$seconds} seconds.");
        }
    }

    /**
     * Generate a unique throttle key for rate limiting.
     */
    protected function throttleKey(ChangePasswordRequest $request, User $user): string
    {
        return Str::lower($user->email) . '|' . $request->ip(); // Combine user email and IP address
    }

    /**
     * Validate password complexity (optional).
     */
    protected function validatePasswordComplexity(string $password)
    {
        if (strlen($password) < 8) {
            throw new \Exception("Password must be at least 8 characters long.");
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new \Exception("Password must contain at least one uppercase letter.");
        }

        if (!preg_match('/[a-z]/', $password)) {
            throw new \Exception("Password must contain at least one lowercase letter.");
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new \Exception("Password must contain at least one number.");
        }

        if (!preg_match('/[\W]/', $password)) { // Special character
            throw new \Exception("Password must contain at least one special character.");
        }
    }
}