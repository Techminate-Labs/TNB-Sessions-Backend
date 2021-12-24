<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name'=>'admin',
            'permissions'=> '{"Users":{"create":true,"view":true,"edit":true,"delete":true,"list":true},"Roles":{"create":true,"view":true,"edit":true,"delete":true,"list":true},"Config":{"create":true,"view":true,"edit":true,"delete":true,"list":true}}',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        
        DB::table('roles')->insert([
            'name'=>'client',
            'permissions'=> '{"Users":{"create":true,"view":true,"edit":true,"delete":true,"list":true},"Roles":{"create":false,"view":false,"edit":false,"delete":false,"list":false},"Config":{"create":false,"view":false,"edit":false,"delete":false,"list":false}}',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
