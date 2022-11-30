<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m031_unemployed extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm031_unemployed';
    // 更新可能な項目の設定
    protected $fillable = [
        'unemployed_code',
        'unemployed_name',
        'unemployed_short_name',
        'unemployed_take_unit_class',
        'holiday_management_class',
        'late_early_leave_class',
        'max_days',
        'paid_leave_target_class',
        'work_day_target_class',
        'deduction_target_class',
        'bonus_absenteeism_deduction_class',
        'manual_grant_enabled_class',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "unemployed_id";
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
                'column' => 'unemployed_code',
                'classes' => null,
                'displayName' => '不就業コード',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'unemployed_name',
                'classes' => null,
                'displayName' => '不就業名称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'unemployed_short_name',
                'classes' => null,
                'displayName' => '不就業略称',
                'type' => 'value',
                'required' => false,
            ],
            3 => [
                'id' => 3,
                'column' => 'unemployed_take_unit_class',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '日数単位',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '半日数単位',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '時間単位',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '有休遅刻早退用',
                    ],
                ],
                'displayName' => '取得単位区分',
                'type' => 'class',
                'required' => true,
            ],
            4 => [
                'id' => 4,
                'column' => 'holiday_management_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '通常不就業',
                    ],
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
                        'displayName' => '予備休暇',
                    ],
                ],
                'displayName' => '休暇管理区分',
                'type' => 'class',
                'required' => true,
            ],
            5 => [
                'id' => 5,
                'column' => 'max_days',
                'classes' => null,
                'displayName' => '上限日数',
                'type' => 'value',
                'required' => true,
            ],
            6 => [
                'id' => 6,
                'column' => 'paid_leave_target_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '無給',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '有給',
                    ],
                ],
                'displayName' => '有給対象区分',
                'type' => 'class',
                'required' => true,
            ],
            7 => [
                'id' => 7,
                'column' => 'work_day_target_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '出勤対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '出勤対象',
                    ],
                ],
                'displayName' => '出勤対象区分',
                'type' => 'class',
                'required' => true,
            ],
            8 => [
                'id' => 8,
                'column' => 'deduction_target_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '控除対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '控除対象',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '育児等控除時間対象',
                    ],
                ],
                'displayName' => '控除対象区分',
                'type' => 'class',
                'required' => true,
            ],
            9 => [
                'id' => 9,
                'column' => 'bonus_absenteeism_deduction_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '控除対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '控除対象',
                    ],
                ],
                'displayName' => '賞与欠勤控除区分',
                'type' => 'class',
                'required' => true,
            ],
            10 => [
                'id' => 10,
                'column' => 'manual_grant_enabled_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '自動付与',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '手動付与',
                    ],
                ],
                'displayName' => '手動付与可否区分',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }
    /**
     * 不就業IDから不就業略称を取得
     */
    public function getShortNameByID($unemployed_id)
    {
        return DB::table($this->table)
            ->select('unemployed_id','unemployed_short_name')
            ->where('unemployed_id', $unemployed_id)
            ->first();
    }

    /**
     * 不就業IDから取得単位区分を取得
     */
    public function getTakeUnitClassByID($unemployed_id)
    {
        return DB::table($this->table)
            ->select('unemployed_id','unemployed_take_unit_class')
            ->where('unemployed_id', $unemployed_id)
            ->first();
    }
    
    /**
     * 不就業IDから勤務実績を取得
     */
    public function getWithBonusAbsenteeismDeduction()
    {
        return DB::table($this->table)
            ->select('unemployed_id')
            ->where('bonus_absenteeism_deduction_class', 1)
            ->where('is_delete', 0)
            ->get();
    }
}
