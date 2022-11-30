<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m027_work_holiday extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm027_work_holiday';
    // 更新可能な項目の設定
    protected $fillable = [
        'work_holiday_name',
        'work_holiday_short_name',
        'work_holiday_class',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "work_holiday_id";
    /**
     * 変更可能なマスタデータの定義
     * type = value >> そのまま表示
     * type = class >> classesからdisplayNameを取得
     */
    public function getMasterDataDefine()
    {
        return [
            0 => [
                'id' => 0,
                'column' => 'work_holiday_name',
                'classes' => null,
                'displayName' => '出休名称',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'work_holiday_short_name',
                'classes' => null,
                'displayName' => '出休略称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'work_holiday_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '通常日',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '法定外休日',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '法定休日',
                    ],
                ],
                'displayName' => '出休区分',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }
}
