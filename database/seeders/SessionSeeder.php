<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sessions')->insert([
            'event_id' => '1',
            'title' => 'Project Setup',
            'date' => '2021.12.27',
            'start' => '02:30',
            'end' => '03:30',
            'meeting_link' => 'zoom',
            'password' => '12321',
            'fee' => '500',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('sessions')->insert([
            'event_id' => '1',
            'title' => 'Basic Commands',
            'date' => '2021.12.28',
            'start' => '02:30',
            'end' => '03:30',
            'meeting_link' => 'zoom',
            'password' => '12321',
            'fee' => '500',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('sessions')->insert([
            'event_id' => '2',
            'title' => 'Project Setup',
            'date' => '2021.12.29',
            'start' => '02:30',
            'end' => '03:30',
            'meeting_link' => 'zoom',
            'password' => '12321',
            'fee' => '500',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
