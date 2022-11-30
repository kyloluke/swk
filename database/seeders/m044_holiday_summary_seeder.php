<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m044_holiday_summary_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 1],
            ['holiday_id' => 1,
            'unemployed_id' => 1,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 2],
            ['holiday_id' => 1,
            'unemployed_id' => 2,
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 3],
            ['holiday_id' => 1,
            'unemployed_id' => 3,
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 4],
            ['holiday_id' => 1,
            'unemployed_id' => 5,
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 5],
            ['holiday_id' => 2,
            'unemployed_id' => 10,
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 6],
            ['holiday_id' => 2,
            'unemployed_id' => 11,
            'detail_no' => 6,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 7],
            ['holiday_id' => 3,
            'unemployed_id' => 15,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 8],
            ['holiday_id' => 4,
            'unemployed_id' => 13,
            'detail_no' => 8,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 9],
            ['holiday_id' => 5,
            'unemployed_id' => 14,
            'detail_no' => 9,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 10],
            ['holiday_id' => 7,
            'unemployed_id' => 24,
            'detail_no' => 10,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 11],
            ['holiday_id' => 7,
            'unemployed_id' => 25,
            'detail_no' => 11,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 12],
            ['holiday_id' => 8,
            'unemployed_id' => 3,
            'detail_no' => 12,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 13],
            ['holiday_id' => 8,
            'unemployed_id' => 4,
            'detail_no' => 13,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 14],
            ['holiday_id' => 8,
            'unemployed_id' => 5,
            'detail_no' => 14,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 15],
            ['holiday_id' => 8,
            'unemployed_id' => 8,
            'detail_no' => 15,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m044_holiday_summary')->updateOrInsert(
            ['holiday_summary_id' => 16],
            ['holiday_id' => 8,
            'unemployed_id' => 9,
            'detail_no' => 16,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
