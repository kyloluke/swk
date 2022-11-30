<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m001_information_type extends MultiTennantModel
{
    use HasFactory;

    // テーブルの関連付け
    protected $table = 'm001_information_type';
    // 更新可能な項目の設定
    protected $fillable = [
        'information_type_name',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "information_type_id";
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
                'column' => 'information_type_name',
                'classes' => null,
                'displayName' => 'インフォメーション種別名称',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
    public function getInformationTypes($office_id, $dept_id)
    {
        return $m001InformationTypedata = DB::table($this->table)
            ->select('information_type_id')
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 会社IDからインフォメーション種別一覧を取得
     */
    public function getInformationTypeList($company_id)
    {
        $information_type_data = DB::table($this->table)
            ->whereIn('company_id', [0, $company_id])
            ->where('is_delete', 0)
            ->get();
        return $information_type_data;
    }
}
