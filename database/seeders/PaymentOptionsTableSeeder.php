<?php

namespace Database\Seeders;

use App\Models\PaymentOption;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $paymentOptions = [
            [
                'name' => 'Cash on Delivery',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Fonepay',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($paymentOptions as $option) {
            
            PaymentOption::firstOrCreate(
                ['name' => $option['name']], 
                $option 
            );
        }
    }
}