<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m043_holiday_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 1],
            ['holiday_name' => '年次有給休暇',
            'holiday_short_name' => '有休',
            'holiday_management_class' => 1,
            'grant_enable_class' => 1,
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
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 2],
            ['holiday_name' => '保存休暇',
            'holiday_short_name' => '保存休',
            'holiday_management_class' => 2,
            'grant_enable_class' => 1,
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
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 3],
            ['holiday_name' => '入社時有給休暇',
            'holiday_short_name' => '入社時有給',
            'holiday_management_class' => 3,
            'grant_enable_class' => 1,
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
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 4],
            ['holiday_name' => '結婚休暇',
            'holiday_short_name' => '結婚休暇',
            'holiday_management_class' => 3,
            'grant_enable_class' => 1,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 5],
            ['holiday_name' => '人間ドック休暇',
            'holiday_short_name' => '人間ドック',
            'holiday_management_class' => 3,
            'grant_enable_class' => 1,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 6],
            ['holiday_name' => '永年勤続休暇',
            'holiday_short_name' => '永勤休暇',
            'holiday_management_class' => 3,
            'grant_enable_class' => 1,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 6,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 7],
            ['holiday_name' => '特別休暇',
            'holiday_short_name' => '特休',
            'holiday_management_class' => 3,
            'grant_enable_class' => 0,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 7,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m043_holiday')->updateOrInsert(
            ['holiday_id' => 8],
            ['holiday_name' => '遅刻早退',
            'holiday_short_name' => '遅早',
            'holiday_management_class' => 3,
            'grant_enable_class' => 0,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 8,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
