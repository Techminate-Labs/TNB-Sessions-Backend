<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profiles')->insert([
            'user_id' => '1',
            'first_name' => 'System',
            'last_name' => 'Admin',
            'mobile' => '01680800810',
            'present_address' => 'Uttara, dhaka, Bangladesh, 1230',
            'permanent_address' => 'Uttara, dhaka, Bangladesh, 1230',
            'identity_number' => '991551221',
            'zip' => '1230',
            'city' => 'Dhaka',
            'image' => 'http://127.0.0.1:8000/images/profile/default.jpg',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('profiles')->insert([
            'user_id' => '2',
            'first_name' => 'POS',
            'last_name' => 'Cashier',
            'mobile' => '01911908431',
            'present_address' => 'Uttara, dhaka, Bangladesh, 1230',
            'permanent_address' => 'Uttara, dhaka, Bangladesh, 1230',
            'identity_number' => '981661333',
            'zip' => '1230',
            'city' => 'Dhaka',
            'image' => 'http://127.0.0.1:8000/images/profile/default.jpg',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
