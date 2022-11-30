<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m046_grant_paid_leave_type_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m046_grant_paid_leave_type')->updateOrInsert(
            ['grant_paid_leave_type_id' => 1],
            ['grant_paid_leave_type_name' => '職員・契約社員(10/1-3/31入社者)',
            'grant_paid_leave_month' => 4,
            'grant_paid_leave_day' => 1,
            'manegement_target_class' => 0,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        DB::table('m046_grant_paid_leave_type')->updateOrInsert(
            ['grant_paid_leave_type_id' => 2],
            ['grant_paid_leave_type_name' => '職員・契約社員(4/1-9/30入社者)',
            'grant_paid_leave_month' => 10,
            'grant_paid_leave_day' => 1,
            'manegement_target_class' => 0,
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        DB::table('m046_grant_paid_leave_type')->updateOrInsert(
            ['grant_paid_leave_type_id' => 3],
            ['grant_paid_leave_type_name' => 'フルタイマー・パート(10/1-3/31入社者)',
            'grant_paid_leave_month' => 4,
            'grant_paid_leave_day' => 16,
            'manegement_target_class' => 0,
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        DB::table('m046_grant_paid_leave_type')->updateOrInsert(
            ['grant_paid_leave_type_id' => 4],
            ['grant_paid_leave_type_name' => 'フルタイマー・パート(4/1-9/30入社者)',
            'grant_paid_leave_month' => 10,
            'grant_paid_leave_day' => 16,
            'manegement_target_class' => 0,
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        DB::table('m046_grant_paid_leave_type')->updateOrInsert(
            ['grant_paid_leave_type_id' => 5],
            ['grant_paid_leave_type_name' => '管理対象外',
            'grant_paid_leave_month' => 4,
            'grant_paid_leave_day' => 1,
            'manegement_target_class' => 1,
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }
}
