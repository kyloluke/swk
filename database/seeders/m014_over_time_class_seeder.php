<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m014_over_time_class_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m014_over_time_class')->updateOrInsert(
            ['over_time_class_id' => 1],
            ['over_time_class' => 1,
            'over_time_class_name' => '時間外',
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m014_over_time_class')->updateOrInsert(
            ['over_time_class_id' => 2],
            ['over_time_class' => 2,
            'over_time_class_name' => '早出',
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m014_over_time_class')->updateOrInsert(
            ['over_time_class_id' => 3],
            ['over_time_class' => 3,
            'over_time_class_name' => '外勤営業時間外',
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
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
