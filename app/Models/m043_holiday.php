<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m043_holiday extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm043_holiday';
    // 更新可能な項目の設定
    protected $fillable = [
        'holiday_name',
        'holiday_short_name',
        'holiday_management_class',
        'grant_enable_class',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "holiday_id";
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
                'column' => 'holiday_name',
                'classes' => null,
                'displayName' => '休暇名称',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'holiday_short_name',
                'classes' => null,
                'displayName' => '休暇略称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'holiday_management_class',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '有給休暇',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '保存休暇',
                    ],
                    [
                        'value' => '3',
                        'displayName' => 'その他休暇',
                    ],
                ],
                'displayName' => '休暇管理区分',
                'type' => 'class',
                'required' => true,
            ],
            3 => [
                'id' => 3,
                'column' => 'grant_enable_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '付与不可',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '付与可',
                    ],
                ],
                'displayName' => '付与可否区分',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }

    /**
     * 休暇情報取得
     */
    public function getHolidayNameById($holiday_id)
    {
        return DB::table($this->table)
            ->select('holiday_name','holiday_short_name')
            ->where('holiday_id', $holiday_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 全休暇情報取得
     */
    public function getHoliday()
    {
        return DB::table($this->table)
            ->where('is_delete', 0)
            ->get();
    }
    
}
