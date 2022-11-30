<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m021_calendar_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m021_calendar')->updateOrInsert(
            ['calendar_id' => 1],
            ['calendar_name' => '全社カレンダ',
            'start_month' => 1,
            'is_holiday_rest' => 1,
            'monday_work_holiday_id' => 1,
            'tuesday_work_holiday_id' => 1,
            'wednesday_work_holiday_id' => 1,
            'thursday_work_holiday_id' => 1,
            'friday_work_holiday_id' => 1,
            'saturday_work_holiday_id' => 2,
            'sunday_work_holiday_id' => 3,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_calendar_id' => 0,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
