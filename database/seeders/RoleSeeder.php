<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            User::ADMIN,
            User::CUSTOMER,
            User::STORE_MANAGER,
            User::CUSTOMER_SUPPORT,
            User::WAREHOUSE_STAFF,
            User::ACCOUNTANT,
            User::MARKETING_MANAGER,
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['guard_name' => 'api', 'name' => $role]);
        }
    }
}
