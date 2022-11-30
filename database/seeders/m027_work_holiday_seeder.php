<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m027_work_holiday_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m027_work_holiday')->updateOrInsert(
            ['work_holiday_id' => 1],
            ['work_holiday_name' => '通常出勤',
            'work_holiday_short_name' => '',
            'work_holiday_class' => 0,
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
        DB::table('m027_work_holiday')->updateOrInsert(
            ['work_holiday_id' => 2],
            ['work_holiday_name' => '所定休日',
            'work_holiday_short_name' => '休',
            'work_holiday_class' => 1,
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
        DB::table('m027_work_holiday')->updateOrInsert(
            ['work_holiday_id' => 3],
            ['work_holiday_name' => '法定休日',
            'work_holiday_short_name' => '法',
            'work_holiday_class' => 2,
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
        // DB::table('m027_work_holiday')->updateOrInsert(
        //     ['work_holiday_id' => 4],
        //     ['work_holiday_name' => '午前休日',
        //     'work_holiday_short_name' => '',
        //     'work_holiday_class' => 0,
        //     'company_id' => 0,
        //     'is_invalid' => 0,
        //     'origin_id' => 0,
        //     'detail_no' => 4,
        //     'is_delete' => 0,
        //     'created_user' => 'SYSTEM',
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_user' => 'SYSTEM',
        //     'updated_at' => date('Y-m-d H:i:s'),
        //     ]
        // );
        // DB::table('m027_work_holiday')->updateOrInsert(
        //     ['work_holiday_id' => 5],
        //     ['work_holiday_name' => '午後休日',
        //     'work_holiday_short_name' => '',
        //     'work_holiday_class' => 0,
        //     'company_id' => 0,
        //     'is_invalid' => 0,
        //     'origin_id' => 0,
        //     'detail_no' => 5,
        //     'is_delete' => 0,
        //     'created_user' => 'SYSTEM',
        //     'created_at' => date('Y-m-d H:i:s'),
        //     'updated_user' => 'SYSTEM',
        //     'updated_at' => date('Y-m-d H:i:s'),
        //     ]
        // );
    }
}
