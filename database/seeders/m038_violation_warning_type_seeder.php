<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m038_violation_warning_type_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 1],
            ['violation_warning_type_name' => '単日時間外時間',
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 2],
            ['violation_warning_type_name' => '月間時間外時間',
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 3],
            ['violation_warning_type_name' => '年間時間外時間',
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 4],
            ['violation_warning_type_name' => '月間時間外時間（特別条項適用）',
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 5],
            ['violation_warning_type_name' => '年間時間外時間（特別条項適用）',
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 6],
            ['violation_warning_type_name' => '２～６か月平均時間外時間',
            'detail_no' => 6,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m038_violation_warning_type')->updateOrInsert(
            ['violation_warning_type_id' => 7],
            ['violation_warning_type_name' => '特別条項適用回数',
            'detail_no' => 7,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
