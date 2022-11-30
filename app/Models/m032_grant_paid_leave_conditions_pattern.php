<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m032_grant_paid_leave_conditions_pattern extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm032_grant_paid_leave_conditions_pattern';
    // 更新可能な項目の設定
    protected $fillable = [
        'attendance_rate_from',
        'attendance_rate_to',
        'round_down_class',
        'grant_rate',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "grant_paid_leave_conditions_pattern_id";
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
                'column' => 'attendance_rate_from',
                'classes' => null,
                'displayName' => '出勤率自',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'attendance_rate_to',
                'classes' => null,
                'displayName' => '出勤率至',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'round_down_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '切り上げ',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '切り捨て',
                    ],
                ],
                'displayName' => '切り捨て区分',
                'type' => 'class',
                'required' => true,
            ],
            3 => [
                'id' => 3,
                'column' => 'grant_rate',
                'classes' => null,
                'displayName' => '付与率',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
}
