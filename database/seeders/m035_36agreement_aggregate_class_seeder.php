<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m035_36agreement_aggregate_class_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 1],
            ['thirtysix_agreement_aggregate_class' => 1,
            'thirtysix_agreement_aggregate_class_name' => '時間外時間（月間）',
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
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 2],
            ['thirtysix_agreement_aggregate_class' => 2,
            'thirtysix_agreement_aggregate_class_name' => '時間外時間（年間）',
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
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 3],
            ['thirtysix_agreement_aggregate_class' => 3,
            'thirtysix_agreement_aggregate_class_name' => '時間外時間（月間・特別条項適用）',
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
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 4],
            ['thirtysix_agreement_aggregate_class' => 4,
            'thirtysix_agreement_aggregate_class_name' => '時間外時間（年間・特別条項適用）',
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
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 5],
            ['thirtysix_agreement_aggregate_class' => 5,
            'thirtysix_agreement_aggregate_class_name' => '２～６か月時間外時間平均',
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
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 6],
            ['thirtysix_agreement_aggregate_class' => 6,
            'thirtysix_agreement_aggregate_class_name' => '削除用ダミー',
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_id' => 0,
            'detail_no' => 6,
            'is_delete' => 1,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
        DB::table('m035_36agreement_aggregate_class')->updateOrInsert(
            ['thirtysix_agreement_aggregate_class_id' => 7],
            ['thirtysix_agreement_aggregate_class' => 7,
            'thirtysix_agreement_aggregate_class_name' => '時間外時間（単日）',
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
    }
}
