<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m008_system_administrator_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m008_system_administrator')->updateOrInsert(
            ['system_administrator_id' => 1],
            ['system_administrator_code' => '1',
            'system_administrator_name' => '管理者1',
            'login_password' => 'admin123',
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m008_system_administrator')->updateOrInsert(
            ['system_administrator_id' => 2],
            ['system_administrator_code' => '2',
            'system_administrator_name' => '管理者2',
            'login_password' => 'admin123',
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
