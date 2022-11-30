<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m028_web_punch_clock_deviation_time_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 余分なレコーダーができていますので、クリアしてから、改めてインサートする。
        DB::table('m028_web_punch_clock_deviation_time')->truncate();

        DB::table('m028_web_punch_clock_deviation_time')->updateOrInsert(
            ['web_punch_clock_deviation_time_id' => 1],
            ['clocking_in_out_id' => 1,
            'allow_before_start_time' => 80,
            'allow_after_end_time' => 0,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 1,
            'is_delete' => 0,
            'field_work_distinguish' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m028_web_punch_clock_deviation_time')->updateOrInsert(
            ['web_punch_clock_deviation_time_id' => 2],
            ['clocking_in_out_id' => 2,
            'allow_before_start_time' => 0,
            'allow_after_end_time' => 70,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 2,
            'is_delete' => 0,
            'field_work_distinguish' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m028_web_punch_clock_deviation_time')->updateOrInsert(
            ['web_punch_clock_deviation_time_id' => 3],
            [
                'clocking_in_out_id' => 2,
                'allow_before_start_time' => 0,
                'allow_after_end_time' => 100,
                'company_id' => 0,
                'is_invalid' => 0,
                'origin_id' => 0,
                'detail_no' => 3,
                'is_delete' => 0,
                'field_work_distinguish' => 1,
                'created_user' => 'SYSTEM',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_user' => 'SYSTEM',
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
