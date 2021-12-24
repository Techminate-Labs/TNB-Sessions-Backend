<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configurations')->insert([
            'app_name' => 'Techminate',
            'time_zone' => 'GMT+6',
            'app_logo' => 'http://127.0.0.1:8000/images/logo/default.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
