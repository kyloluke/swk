<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m039_prevention_overwork_check extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm039_prevention_overwork_check';
    // 更新可能な項目の設定
    protected $fillable = [
        'violation_warning_type_id',
        'violation_warning_id',
        'time_or_count',
        'message',
        'valid_date_start',
        'valid_date_end',
        'valid_class',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "prevention_overwork_check_id";
    public function violation_warning_type()
    {
        return $this->belongsTo('App\Models\m038_violation_warning_type', 'violation_warning_type_id');
    }
    public function violation_warning()
    {
        return $this->belongsTo('App\Models\m037_violation_warning', 'violation_warning_id');
    }
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
                'column' => 'violation_warning_type_id',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '単日時間外時間',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '月間時間外時間',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '年間時間外時間',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '月間時間外時間（特別条項適用）',
                    ],
                    [
                        'value' => '5',
                        'displayName' => '年間時間外時間（特別条項適用）',
                    ],
                    [
                        'value' => '6',
                        'displayName' => '２～６か月平均時間外時間',
                    ],
                    [
                        'value' => '7',
                        'displayName' => '特別条項適用回数',
                    ],
                ],
                'displayName' => '違反警告名称',
                'type' => 'class',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'violation_warning_id',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '正常',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '乖離',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '違反',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '警告',
                    ],
                ],
                'displayName' => '違反警告種別',
                'type' => 'class',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'time_or_count',
                'classes' => null,
                'displayName' => '時間/回数',
                'type' => 'value',
                'required' => true,
            ],
            3 => [
                'id' => 3,
                'column' => 'message',
                'classes' => null,
                'displayName' => 'メッセージ',
                'type' => 'value',
                'required' => false,
            ],
            4 => [
                'id' => 4,
                'column' => 'valid_date_start',
                'classes' => null,
                'displayName' => '有効年月日開始',
                'type' => 'value',
                'required' => true,
            ],
            5 => [
                'id' => 5,
                'column' => 'valid_date_end',
                'classes' => null,
                'displayName' => '有効年月日終了',
                'type' => 'value',
                'required' => true,
            ],
            6 => [
                'id' => 6,
                'column' => 'valid_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '無効',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '有効',
                    ],
                ],
                'displayName' => '有効区分',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }
}
