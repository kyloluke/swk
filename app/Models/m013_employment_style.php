<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m013_employment_style extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm013_employment_style';
    // 更新可能な項目の設定
    protected $fillable = [
        'employment_style_name',
        'employment_style_short_name',
        'hourly_wage_target',
        'manegement_free_time',
        'absence_deduction_target',
        'childcare_deduction_target',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "employment_style_id";
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
                'column' => 'employment_style_name',
                'classes' => null,
                'displayName' => '雇用形態名称',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'employment_style_short_name',
                'classes' => null,
                'displayName' => '雇用形態略称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'hourly_wage_target',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '対象',
                    ],
                ],
                'displayName' => '時給対象',
                'type' => 'class',
                'required' => false,
            ],
            3 => [
                'id' => 3,
                'column' => 'manegement_free_time',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '管理職',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '裁量労働',
                    ],
                ],
                'displayName' => '管理職裁量労働対象',
                'type' => 'class',
                'required' => true,
            ],
            4 => [
                'id' => 4,
                'column' => 'absence_deduction_target',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '対象',
                    ],
                ],
                'displayName' => '欠勤控除対象',
                'type' => 'class',
                'required' => true,
            ],
            5 => [
                'id' => 5,
                'column' => 'childcare_deduction_target',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '対象外',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '対象',
                    ],
                ],
                'displayName' => '育児等控除対象',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }
    public function getName($employment_style_id)
    {
        $employment_style_name = DB::table($this->table)
            ->select(
                'employment_style_name'
            )
            ->where('employment_style_id', $employment_style_id)
            ->where('is_delete', 0)
            ->first();
        return $employment_style_name;
    }
    public function getShortName($employment_style_id)
    {
        $employment_style_short_name = DB::table($this->table)
            ->select(
                'employment_style_short_name'
            )
            ->where('employment_style_id', $employment_style_id)
            ->where('is_delete', 0)
            ->first();
        return $employment_style_short_name;
    }
    /**
     * 雇用形態IDを取得
     */
    public function checkId($employment_style_id)
    {
        return DB::table($this->table)
            ->select('employment_style_id')
            ->where('employment_style_id', $employment_style_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 会社IDから,役職名一覧を取得
     */
    public function getEmploymentStyleList()
    {
        $employment_style_data = DB::table($this->table)
            ->select('employment_style_id',
                    'employment_style_name',
                    'employment_style_short_name',
                    'hourly_wage_target',
                    'manegement_free_time',
                    'absence_deduction_target',
                    'childcare_deduction_target')
            ->where('is_delete', 0)
            ->get();
        return $employment_style_data;
    }
}
