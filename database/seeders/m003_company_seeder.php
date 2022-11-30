<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class m003_company_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m003_company')->updateOrInsert(
            ['company_id' => 1],
            ['company_code' => '1',
            'company_name' => 'シヤチハタ株式会社',
            'company_short_name' => 'シヤチハタ',
            'beginning_month' => 7,
            'valid_date_start' => 0,
            'valid_date_end' => 2958465,
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
