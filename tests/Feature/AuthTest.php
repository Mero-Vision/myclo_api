<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_checks_user_authentication_logic()
    {
        // Create user
        $user = User::factory()->create([
            'password' => Hash::make('password123')
        ]);

        // Manually attempt login
        $credentials = [
            'email' => $user->email,
            'password' => 'password123'
        ];

        $this->assertTrue(Auth::attempt($credentials));
        $this->assertEquals(Auth::user()->id, $user->id);
    }

    /** @test */
    public function it_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password')
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'wrong-password'
        ];

        $this->assertFalse(Auth::attempt($credentials));
        $this->assertGuest();
    }

    /** @test */
    public function it_logs_out_user()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->assertAuthenticatedAs($user);

        Auth::logout();

        $this->assertGuest();
    }
}