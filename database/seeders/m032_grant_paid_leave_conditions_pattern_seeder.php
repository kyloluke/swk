<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m032_grant_paid_leave_conditions_pattern_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m032_grant_paid_leave_conditions_pattern')->updateOrInsert(
            ['grant_paid_leave_conditions_pattern_id' => 1],
            ['attendance_rate_from' => 0,
            'attendance_rate_to' => 79,
            'round_down_class' => 0,
            'grant_rate' => 0,
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
        DB::table('m032_grant_paid_leave_conditions_pattern')->updateOrInsert(
            ['grant_paid_leave_conditions_pattern_id' => 2],
            ['attendance_rate_from' => 80,
            'attendance_rate_to' => 100,
            'round_down_class' => 0,
            'grant_rate' => 100,
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
    }
}
