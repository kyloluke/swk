<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m015_deduction_reason extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm015_deduction_reason';
    // 更新可能な項目の設定
    protected $fillable = [
        'deduction_reason',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "deduction_reason_id";
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
                'column' => 'deduction_reason',
                'classes' => null,
                'displayName' => '控除事由名称',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
}
