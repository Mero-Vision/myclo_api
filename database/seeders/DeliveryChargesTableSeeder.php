<?php

namespace Database\Seeders;

use App\Models\DeliveryCharge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryChargesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Array of all districts in Nepal
        $districts = [
            "Kathmandu Inside Ring Road",
            "Kathmandu Outside Ring Road",
            "Achham",
            "Arghakhanchi",
            "Baglung",
            "Baitadi",
            "Bajhang",
            "Bajura",
            "Banke",
            "Bara",
            "Bardiya",
            "Bhaktapur",
            "Bhojpur",
            "Chitwan",
            "Dadeldhura",
            "Dailekh",
            "Dang",
            "Darchula",
            "Dhading",
            "Dhankuta",
            "Dhanusa",
            "Dholkha",
            "Dolpa",
            "Doti",
            "Gorkha",
            "Gulmi",
            "Humla",
            "Ilam",
            "Jajarkot",
            "Jhapa",
            "Jumla",
            "Kailali",
            "Kalikot",
            "Kanchanpur",
            "Kapilvastu",
            "Kaski",
            // "Kathmandu",
            "Kavrepalanchok",
            "Khotang",
            "Lalitpur",
            "Lamjung",
            "Mahottari",
            "Makwanpur",
            "Manang",
            "Morang",
            "Mugu",
            "Mustang",
            "Myagdi",
            "Nawalpur",
            "Nuwakot",
            "Okhaldhunga",
            "Palpa",
            "Panchthar",
            "Parbat",
            "Parsa",
            "Pyuthan",
            "Ramechhap",
            "Rasuwa",
            "Rautahat",
            "Rolpa",
            "Rukum",
            "Rupandehi",
            "Salyan",
            "Sankhuwasabha",
            "Saptari",
            "Sarlahi",
            "Sindhuli",
            "Sindhupalchok",
            "Siraha",
            "Solukhumbu",
            "Sunsari",
            "Surkhet",
            "Syangja",
            "Tanahu",
            "Taplejung",
            "Terhathum",
            "Udayapur"
        ];

        foreach ($districts as $district) {
            DeliveryCharge::firstOrCreate([
                'district_name' => $district,
            ], [
                'cost_0_1kg' => 0,
                'cost_1_2kg' => 0,
                'cost_2_3kg' => 0,
                'cost_3_5kg' => 0,
                'cost_5_10kg' => 0,
                'cost_above_10kg' => 0,
            ]);
        }
    }
}
