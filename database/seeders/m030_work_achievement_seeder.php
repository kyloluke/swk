<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m030_work_achievement_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 1],
            ['work_achievement_name' => '通常勤務',
            'work_achievement_short_name' => ' ', //【暫定】PAC_10-243を対応するまでの暫定対応。「通常出勤」を表示しないために空文字にしたいがWEB打刻一覧で休日扱いされてしまうため半角スペース入れる
            'work_achievement_display_class' => 2,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 2],
            ['work_achievement_name' => '出張(国内)',
            'work_achievement_short_name' => '出張(国内)',
            'work_achievement_display_class' => 2,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 3],
            ['work_achievement_name' => '出張(海外)',
            'work_achievement_short_name' => '出張(海外)',
            'work_achievement_display_class' => 2,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 4],
            ['work_achievement_name' => '休日勤務(振替休日申請なし)',
            'work_achievement_short_name' => '休出(振替なし)',
            'work_achievement_display_class' => 3,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 5],
            ['work_achievement_name' => '休日出張(振替休日申請なし)',
            'work_achievement_short_name' => '休出張(振替なし)',
            'work_achievement_display_class' => 3,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 6],
            ['work_achievement_name' => '休日出張(移動のみ)',
            'work_achievement_short_name' => '休出張(移動のみ)',
            'work_achievement_display_class' => 3,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 7],
            ['work_achievement_name' => '休日勤務(振替休日申請あり)',
            'work_achievement_short_name' => '休出(振替あり)',
            'work_achievement_display_class' => 4,
            'is_not_register' => 0,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 8],
            ['work_achievement_name' => '非在籍',
            'work_achievement_short_name' => '非在籍',
            'work_achievement_display_class' => 1,
            'is_not_register' => 1,
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
        DB::table('m030_work_achievement')->updateOrInsert(
            ['work_achievement_id' => 9],
            ['work_achievement_name' => '振休',
            'work_achievement_short_name' => '振休',
            'work_achievement_display_class' => 5,
            'is_not_register' => 1,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 9,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
