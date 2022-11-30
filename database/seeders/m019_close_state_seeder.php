<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m019_close_state_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m019_close_state')->updateOrInsert(
            ['close_state_id' => 1],
            ['close_state_class' => 1,
            'close_state_name' => '初期状態',
            'close_state_short_name' => '',
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m019_close_state')->updateOrInsert(
            ['close_state_id' => 2],
            ['close_state_class' => 2,
            'close_state_name' => '申請済み',
            'close_state_short_name' => '申請済',
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m019_close_state')->updateOrInsert(
            ['close_state_id' => 3],
            ['close_state_class' => 3,
            'close_state_name' => '承認済み',
            'close_state_short_name' => '承認済',
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m019_close_state')->updateOrInsert(
            ['close_state_id' => 4],
            ['close_state_class' => 4,
            'close_state_name' => '事業所締め',
            'close_state_short_name' => '事業締',
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m019_close_state')->updateOrInsert(
            ['close_state_id' => 5],
            ['close_state_class' => 5,
            'close_state_name' => '全社締め',
            'close_state_short_name' => '全社締',
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
