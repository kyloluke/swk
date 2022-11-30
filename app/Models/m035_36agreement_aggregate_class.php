<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m035_36agreement_aggregate_class extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm035_36agreement_aggregate_class';
    // 更新可能な項目の設定
    protected $fillable = [
        'thirtysix_agreement_aggregate_class',
        'thirtysix_agreement_aggregate_class_name',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "thirtysix_agreement_aggregate_class_id";
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
                'column' => 'thirtysix_agreement_aggregate_class',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '時間外時間（単日）',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '時間外時間（月間）',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '時間外時間（年間）',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '時間外時間（月間・特別条項適用）',
                    ],
                    [
                        'value' => '5',
                        'displayName' => '時間外時間（年間・特別条項適用）',
                    ],
                    [
                        'value' => '6',
                        'displayName' => '削除用',
                    ],
                    [
                        'value' => '7',
                        'displayName' => '２～６か月時間外時間平均',
                    ],
                ],
                'displayName' => '36協定集計単位区分',
                'type' => 'class',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'thirtysix_agreement_aggregate_class_name',
                'classes' => null,
                'displayName' => '36協定集計単位区分名称',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
}
