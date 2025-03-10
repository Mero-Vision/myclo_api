<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            'name' => 'Admin',
            'email' => 'info@myclo.net',
            'password' => Hash::make('Password'),
            'email_verified_at' => Carbon::now()
        ];
        DB::beginTransaction();
        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            $userData
        );
        $user->assignRole(User::ADMIN);
        DB::commit();
    }
}