<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m036_36agreement_max_time_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 1],
            ['thirtysix_agreement_id' => 1,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 2],
            ['thirtysix_agreement_id' => 2,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 3],
            ['thirtysix_agreement_id' => 4,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 4],
            ['thirtysix_agreement_id' => 5,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 4,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 5],
            ['thirtysix_agreement_id' => 1,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 1,
            'detail_no' => 5,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 6],
            ['thirtysix_agreement_id' => 2,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 6,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 7],
            ['thirtysix_agreement_id' => 4,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 7,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 8],
            ['thirtysix_agreement_id' => 5,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 8,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 9],
            ['thirtysix_agreement_id' => 1,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 9,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 10],
            ['thirtysix_agreement_id' => 2,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 10,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 11],
            ['thirtysix_agreement_id' => 4,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 11,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 12],
            ['thirtysix_agreement_id' => 5,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 1,
            'detail_no' => 12,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 13],
            ['thirtysix_agreement_id' => 1,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 13,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 14],
            ['thirtysix_agreement_id' => 2,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 14,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 15],
            ['thirtysix_agreement_id' => 4,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 15,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 16],
            ['thirtysix_agreement_id' => 5,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 16,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 17],
            ['thirtysix_agreement_id' => 1,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 17,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 18],
            ['thirtysix_agreement_id' => 2,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 18,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 19],
            ['thirtysix_agreement_id' => 4,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 19,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 20],
            ['thirtysix_agreement_id' => 5,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 20,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 21],
            ['thirtysix_agreement_id' => 2,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 6,
            'max_time' => 4800,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 21,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 22],
            ['thirtysix_agreement_id' => 5,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 6,
            'max_time' => 4800,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 22,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 23],
            ['thirtysix_agreement_id' => 7,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 23,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 24],
            ['thirtysix_agreement_id' => 8,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 24,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 25],
            ['thirtysix_agreement_id' => 10,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 25,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 26],
            ['thirtysix_agreement_id' => 11,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 480,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 26,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 27],
            ['thirtysix_agreement_id' => 7,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 1,
            'detail_no' => 27,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 28],
            ['thirtysix_agreement_id' => 8,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 28,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 29],
            ['thirtysix_agreement_id' => 10,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 29,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 30],
            ['thirtysix_agreement_id' => 11,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 2700,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 30,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 31],
            ['thirtysix_agreement_id' => 7,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 31,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 32],
            ['thirtysix_agreement_id' => 8,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 32,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 33],
            ['thirtysix_agreement_id' => 10,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 33,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 34],
            ['thirtysix_agreement_id' => 11,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 21600,
            'holiday_work_aggregate_class' => 1,
            'detail_no' => 34,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 35],
            ['thirtysix_agreement_id' => 7,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 35,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 36],
            ['thirtysix_agreement_id' => 8,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 36,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 37],
            ['thirtysix_agreement_id' => 10,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 37,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 38],
            ['thirtysix_agreement_id' => 11,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 5940,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 38,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 39],
            ['thirtysix_agreement_id' => 7,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 39,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 40],
            ['thirtysix_agreement_id' => 8,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 40,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 41],
            ['thirtysix_agreement_id' => 10,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 41,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 42],
            ['thirtysix_agreement_id' => 11,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 43200,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 42,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 43],
            ['thirtysix_agreement_id' => 8,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 6,
            'max_time' => 4800,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 43,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 44],
            ['thirtysix_agreement_id' => 11,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 6,
            'max_time' => 4800,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 44,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 45],
            ['thirtysix_agreement_id' => 3,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 45,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 46],
            ['thirtysix_agreement_id' => 6,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 46,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 47],
            ['thirtysix_agreement_id' => 9,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 47,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 48],
            ['thirtysix_agreement_id' => 12,
            'thirtysix_agreement_aggregate_class_id' => 7,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 48,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 49],
            ['thirtysix_agreement_id' => 3,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 49,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 50],
            ['thirtysix_agreement_id' => 6,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 50,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 51],
            ['thirtysix_agreement_id' => 9,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 51,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 52],
            ['thirtysix_agreement_id' => 12,
            'thirtysix_agreement_aggregate_class_id' => 1,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 52,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 53],
            ['thirtysix_agreement_id' => 3,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 53,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 54],
            ['thirtysix_agreement_id' => 6,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 54,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 55],
            ['thirtysix_agreement_id' => 9,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 55,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 56],
            ['thirtysix_agreement_id' => 12,
            'thirtysix_agreement_aggregate_class_id' => 2,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 56,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 57],
            ['thirtysix_agreement_id' => 3,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 57,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 58],
            ['thirtysix_agreement_id' => 6,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 58,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 59],
            ['thirtysix_agreement_id' => 9,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 59,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 60],
            ['thirtysix_agreement_id' => 12,
            'thirtysix_agreement_aggregate_class_id' => 3,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 60,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 61],
            ['thirtysix_agreement_id' => 3,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 61,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 62],
            ['thirtysix_agreement_id' => 6,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 62,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 63],
            ['thirtysix_agreement_id' => 9,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 63,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 64],
            ['thirtysix_agreement_id' => 12,
            'thirtysix_agreement_aggregate_class_id' => 4,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 64,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 65],
            ['thirtysix_agreement_id' => 3,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 65,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 66],
            ['thirtysix_agreement_id' => 6,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 66,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 67],
            ['thirtysix_agreement_id' => 9,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 67,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m036_36agreement_max_time')->updateOrInsert(
            ['thirtysix_agreement_max_time_id' => 68],
            ['thirtysix_agreement_id' => 12,
            'thirtysix_agreement_aggregate_class_id' => 5,
            'thirtysix_agreement_aggregate_unit' => 1,
            'max_time' => 0,
            'holiday_work_aggregate_class' => 0,
            'detail_no' => 68,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
