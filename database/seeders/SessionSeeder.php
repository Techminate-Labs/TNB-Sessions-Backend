<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            'start' => '2021.12.25',
            'end' => '2021.12.25',
            'meeting_link' => 'zoom',
            'password' => '12321',
            'payment' => '500',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('sessions')->insert([
            'event_id' => '1',
            'title' => 'Basic Commands',
            'start' => '2021.12.25',
            'end' => '2021.12.25',
            'meeting_link' => 'zoom',
            'password' => '12321',
            'payment' => '500',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('sessions')->insert([
            'event_id' => '2',
            'title' => 'Project Setup',
            'start' => '2021.12.25',
            'end' => '2021.12.25',
            'meeting_link' => 'zoom',
            'password' => '12321',
            'payment' => '500',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
