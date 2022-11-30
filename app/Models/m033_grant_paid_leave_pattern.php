<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m033_grant_paid_leave_pattern extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm033_grant_paid_leave_pattern';
    // 更新可能な項目の設定
    protected $fillable = [
        'prescribed_week_days',
        'service_length_from',
        'service_length_to',
        'grant_paid_leave_days',
        'obligatory_take_paid_leave_days',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "grant_paid_leave_pattern_id";
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
                'column' => 'prescribed_week_days',
                'classes' => null,
                'displayName' => '週所定日数',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'service_length_from',
                'classes' => null,
                'displayName' => '勤続月数自',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'service_length_to',
                'classes' => null,
                'displayName' => '勤続月数至',
                'type' => 'value',
                'required' => true,
            ],
            3 => [
                'id' => 3,
                'column' => 'grant_paid_leave_days',
                'classes' => null,
                'displayName' => '有給付与日数',
                'type' => 'value',
                'required' => true,
            ],
            4 => [
                'id' => 4,
                'column' => 'obligatory_take_paid_leave_days',
                'classes' => null,
                'displayName' => '年間有給取得義務日数',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }

    /**
     * 有給付与日数パターンIDを取得
     */
    public function checkId($grant_paid_leave_pattern_id)
    {
        return DB::table($this->table)
            ->select('grant_paid_leave_pattern_id')
            ->where('grant_paid_leave_pattern_id', $grant_paid_leave_pattern_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 有給付与日数パターンIDを取得
     */
    public function getGrantPaidLeavePattern($prescribed_week_days,$all_year_work_month)
    {
        return DB::table($this->table)
            ->where('prescribed_week_days', $prescribed_week_days)
            ->where('service_length_from','<=', $all_year_work_month)
            ->where('service_length_to','>=', $all_year_work_month)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 有給付与日数パターンIDを取得
     */
    public function getAllBYGrantPaidLeaveDays($prescribed_week_days,$grant_paid_leave_days)
    {
        return DB::table($this->table)
            ->where('prescribed_week_days', $prescribed_week_days)
            ->where('grant_paid_leave_days', $grant_paid_leave_days)
            ->where('is_delete', 0)
            ->first();
    }

}
