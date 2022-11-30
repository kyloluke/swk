<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m018_approval_state_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m018_approval_state')->updateOrInsert(
            ['approval_state_id' => 1],
            ['approval_state_class' => 1,
            'approval_state_name' => '初期状態',
            'approval_state_short_name' => '',
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m018_approval_state')->updateOrInsert(
            ['approval_state_id' => 2],
            ['approval_state_class' => 2,
            'approval_state_name' => '申請中',
            'approval_state_short_name' => '申請',
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m018_approval_state')->updateOrInsert(
            ['approval_state_id' => 3],
            ['approval_state_class' => 3,
            'approval_state_name' => '承認済み',
            'approval_state_short_name' => '承認',
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m018_approval_state')->updateOrInsert(
            ['approval_state_id' => 4],
            ['approval_state_class' => 4,
            'approval_state_name' => '差戻',
            'approval_state_short_name' => '差戻',
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m018_approval_state')->updateOrInsert(
            ['approval_state_id' => 5],
            ['approval_state_class' => 5,
            'approval_state_name' => '仮申請',
            'approval_state_short_name' => '',
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
