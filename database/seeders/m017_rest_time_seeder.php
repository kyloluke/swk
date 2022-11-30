<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m017_rest_time_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m017_rest_time')->updateOrInsert(
            ['rest_time_id' => 1],
            ['rest_time_name' => '所定休憩45分',
            'work_time_from' => 360,
            'work_time_to' => 479,
            'rest_time' => 45,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m017_rest_time')->updateOrInsert(
            ['rest_time_id' => 2],
            ['rest_time_name' => '所定休憩60分',
            'work_time_from' => 480,
            'work_time_to' => 1440,
            'rest_time' => 60,
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m017_rest_time')->updateOrInsert(
            ['rest_time_id' => 3],
            ['rest_time_name' => '削除確認用ダミー',
            'work_time_from' => 9999,
            'work_time_to' => 9999,
            'rest_time' => 30,
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
