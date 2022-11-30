<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m040_36agreement_apply_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m040_36agreement_apply')->updateOrInsert(
            ['thirtysix_agreement_apply_id' => 1],
            ['thirtysix_agreement_apply_name' => '一般（特別条項なし）',
            'thirtysix_agreement_apply_class' => 1,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_thirtysix_agreement_apply_id' => 0,
            'detail_no' => 1,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        DB::table('m040_36agreement_apply')->updateOrInsert(
            ['thirtysix_agreement_apply_id' => 2],
            ['thirtysix_agreement_apply_name' => '一般（特別条項あり）',
            'thirtysix_agreement_apply_class' => 1,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_thirtysix_agreement_apply_id' => 0,
            'detail_no' => 2,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        DB::table('m040_36agreement_apply')->updateOrInsert(
            ['thirtysix_agreement_apply_id' => 3],
            ['thirtysix_agreement_apply_name' => '対象外',
            'thirtysix_agreement_apply_class' => 9,
            'company_id' => 0,
            'is_invalid' => 0,
            'origin_thirtysix_agreement_apply_id' => 0,
            'detail_no' => 3,
            'is_delete' => 0,
            'created_user' => 'SYSTEM',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_user' => 'SYSTEM',
            'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }
}
