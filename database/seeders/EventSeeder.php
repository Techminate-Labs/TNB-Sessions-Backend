<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            'user_id' => '1',
            'title' => 'discord bot',
            'start' => '2021.12.25',
            'end' => '2021.12.25',
            'total hour' => '2',
            'media' => 'zoom',
            'pay_type' => 'paid',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
