<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m030_work_achievement extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm030_work_achievement';
    // 更新可能な項目の設定
    protected $fillable = [
        'work_achievement_name',
        'work_achievement_short_name',
        'work_achievement_display_class',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "work_achievement_id";
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
                'column' => 'work_achievement_name',
                'classes' => null,
                'displayName' => '実績名称',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'work_achievement_short_name',
                'classes' => null,
                'displayName' => '実績略称',
                'type' => 'value',
                'required' => false,
            ],
            2 => [
                'id' => 2,
                'column' => 'work_achievement_display_class',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '共通',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '通常出勤日',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '休日',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '休日（振替あり）',
                    ],
                    [
                        'value' => '5',
                        'displayName' => '振替休日',
                    ],
                ],
                'displayName' => '実績区分',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }
    
    /**
     * 勤務実績IDから勤務実績を取得
     */
    public function getShortNameByID($work_achievement_id)
    {
        return DB::table($this->table)
            ->select('work_achievement_short_name', 'work_achievement_display_class')
            ->where('work_achievement_id', $work_achievement_id)
            ->first();
    }

    /**
     * 勤務実績IDから実績表示区分を取得
     */
    public function getWorkAchievementDisplayClassByID($work_achievement_id)
    {
        return DB::table($this->table)
            ->select('work_achievement_display_class')
            ->where('work_achievement_id', $work_achievement_id)
            ->first();
    }
}
