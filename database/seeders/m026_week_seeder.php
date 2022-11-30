<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m026_week_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 1],
            ['week_name' => '月曜日',
            'week_short_name' => '月',
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 2],
            ['week_name' => '火曜日',
            'week_short_name' => '火',
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 3],
            ['week_name' => '水曜日',
            'week_short_name' => '水',
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 4],
            ['week_name' => '木曜日',
            'week_short_name' => '木',
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 5],
            ['week_name' => '金曜日',
            'week_short_name' => '金',
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 6],
            ['week_name' => '土曜日',
            'week_short_name' => '土',
            'detail_no' => 6,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m026_week')->updateOrInsert(
            ['week_id' => 7],
            ['week_name' => '日曜日',
            'week_short_name' => '日',
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
