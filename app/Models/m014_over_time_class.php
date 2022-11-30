<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m014_over_time_class extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm014_over_time_class';
    // 更新可能な項目の設定
    protected $fillable = [
        'over_time_class',
        'over_time_class_name',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "over_time_class_id";
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
                'column' => 'over_time_class',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '時間外',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '早出',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '外勤営業時間外',
                    ],
                ],
                'displayName' => '時間外区分',
                'type' => 'class',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'over_time_class_name',
                'classes' => null,
                'displayName' => '時間外区分名称',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
}
