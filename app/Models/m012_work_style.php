<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m012_work_style extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm012_work_style';
    // 更新可能な項目の設定
    protected $fillable = [
        'work_style_name',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "work_style_id";
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
                'column' => 'work_style_name',
                'classes' => null,
                'displayName' => '勤務形態名称',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
}
