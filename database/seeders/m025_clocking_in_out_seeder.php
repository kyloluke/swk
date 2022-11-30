<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m025_clocking_in_out_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m025_clocking_in_out')->updateOrInsert(
            ['clocking_in_out_id' => 1],
            ['clocking_in_out_name' => '出勤',
            'clocking_in_out_short_name' => '出',
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m025_clocking_in_out')->updateOrInsert(
            ['clocking_in_out_id' => 2],
            ['clocking_in_out_name' => '退勤',
            'clocking_in_out_short_name' => '退',
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m025_clocking_in_out')->updateOrInsert(
            ['clocking_in_out_id' => 3],
            ['clocking_in_out_name' => '外出',
            'clocking_in_out_short_name' => '外',
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m025_clocking_in_out')->updateOrInsert(
            ['clocking_in_out_id' => 4],
            ['clocking_in_out_name' => '戻り',
            'clocking_in_out_short_name' => '戻',
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
