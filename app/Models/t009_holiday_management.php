<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t009_holiday_management extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't009_holiday_management';
    // 更新可能な項目の設定
    protected $fillable = [
        'holiday_id',
        'employee_id',
        'grant_date',
        'next_grant_paid_leave_date',
        'grant_holiday_days',
        'grant_holiday_half_days',
        'acquired_holiday_days',
        'acquired_holiday_half_days',
        'acquired_holiday_time',
        'remaining_holiday_days',
        'remaining_holiday_half_days',
        'remaining_holiday_time',
        'valid_date_start',
        'valid_date_end',
        'accumulated_paid_leave_transition_class',
        'priority_use_class',
        'obligatory_take_paid_leave_days',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "holiday_management_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function getData()
    {
        $t009HolidayManagement = DB::table($this->table)->get();

        return $t009HolidayManagement;
    }

    /**
     * 休暇情報取得
     */
    public function getHolidays($employee_id,$holiday_id)
    {
        return DB::table($this->table)
            ->select('holiday_management_id','holiday_id','grant_date', 'grant_holiday_days', 'acquired_holiday_days', 'remaining_holiday_days', 'valid_date_end')
            ->where('employee_id', $employee_id)
            ->where('holiday_id', $holiday_id)
            ->where('is_delete', 0)
            ->orderby('grant_date', 'asc')
            ->get();
    }

    /**
     * 予備休暇情報取得
     */
    public function getReserveHolidays($employee_id)
    {
        return DB::table($this->table)
            ->select('holiday_management_id','holiday_id','grant_date', 'grant_holiday_days', 'acquired_holiday_days', 'remaining_holiday_days', 'valid_date_end')
            ->where('employee_id', $employee_id)
            ->where('holiday_id', '>', 2)
            ->where('is_delete', 0)
            ->orderby('grant_date', 'asc')
            ->get();
    }

    /**
     * 期間内休暇情報取得
     */
    public function getThisHoliday($employee_id,$today_serial,$holiday_id)
    {
        return DB::table($this->table)
            ->select('grant_date', 'remaining_holiday_days', 'next_grant_paid_leave_date')
            ->where('employee_id', $employee_id)
            ->where('holiday_id', $holiday_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 有効期間内休暇情報全取得
     */
    public function getTargetHolidays($employee_id,$today_serial,$holiday_ids)
    {
        return DB::table($this->table)
            ->select('remaining_holiday_days', 'remaining_holiday_half_days')
            ->where('employee_id', $employee_id)
            ->whereIn('holiday_id', $holiday_ids)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->orderby('grant_date', 'asc')
            ->get();
    }
    /**
     * 有効有休情報取得
     */
    public function getValidHolidays($employee_id, $firstday_of_month, $lastday_of_month)
    {
        $validHolidaysList = array();

        $validHolidays = DB::table($this->table)
            ->select('valid_date_start','valid_date_end','remaining_holiday_days','remaining_holiday_half_days')
            ->where('employee_id', $employee_id)
            ->where(function($query)
                {
                    $query->where('holiday_id', '=', 1)
                          ->orWhere('holiday_id', '=', 3);
                })
            ->where('valid_date_start', '<=', $lastday_of_month)
            ->where('valid_date_end', '>=', $firstday_of_month)
            ->where('is_delete', 0)
            ->get();

        foreach($validHolidays as $valid_holiday_info){
            //有休残数を計算
            $total_remaining_holiday_days = $valid_holiday_info->remaining_holiday_days + ($valid_holiday_info->remaining_holiday_half_days/2);
            //有効開始日、有効終了日、残数を配列に格納
            $validHolidaysList[] = [
                'valid_date_start' => $valid_holiday_info->valid_date_start, 
                'valid_date_end' => $valid_holiday_info->valid_date_end, 
                'total_remaining_holiday_days' => $total_remaining_holiday_days,
            ];
        }
        return $validHolidaysList;
    }

    /**
     * 有休・保存休残数更新
     */
    public function updateRemainingHoliday($employee_id, $firstday_of_month, $lastday_of_month, $acquired_paid_leave_days, $kind)
    {
        if($kind == 1){
            //有休
            $validHolidays = DB::table($this->table)
                ->select('holiday_management_id', 'remaining_holiday_days','remaining_holiday_half_days', 'acquired_holiday_days', 'acquired_holiday_half_days')
                ->where('employee_id', $employee_id)
                ->where(function($query)
                    {
                        $query->where('holiday_id', '=', 1)
                            ->orWhere('holiday_id', '=', 3);
                    })
                ->where('valid_date_start', '<=', $lastday_of_month)
                ->where('valid_date_end', '>=', $firstday_of_month)
                ->where('is_delete', 0)
                ->orderBy('valid_date_end', 'asc')
                ->get();
        }
        else if($kind == 2){
            //保存休
            $validHolidays = DB::table($this->table)
                ->select('holiday_management_id', 'remaining_holiday_days','remaining_holiday_half_days', 'acquired_holiday_days', 'acquired_holiday_half_days')
                ->where('employee_id', $employee_id)
                ->where(function($query)
                    {
                        $query->where('holiday_id', '=', 2);
                    })
                ->where('valid_date_start', '<=', $lastday_of_month)
                ->where('valid_date_end', '>=', $firstday_of_month)
                ->where('is_delete', 0)
                ->orderBy('grant_date', 'asc')
                ->get();
        }
        else{
            return 1;
        }

        //残数チェック
        $total_remaining_holiday_days = 0;
        foreach($validHolidays as $holidays){
            $total_remaining_holiday_days += $holidays->remaining_holiday_days + ($holidays->remaining_holiday_half_days/2);
        }
        if($total_remaining_holiday_days < $acquired_paid_leave_days){
            return 2;
        }

        $sub_acquired_paid_leave_days = $acquired_paid_leave_days; //有休使用日数減算ワーク
        foreach($validHolidays as $holidays){
            $total_remaining_holiday_days = $holidays->remaining_holiday_days + ($holidays->remaining_holiday_half_days/2);
            $sum_acquired_paid_leave_days = 0; //有休使用日数加算ワーク
            //残数から引く数が0なら減算終了
            if($sub_acquired_paid_leave_days == 0){
                break;
            }
            //残数が0なら次の有休に
            if($total_remaining_holiday_days == 0){
                continue;
            }
            //残数足りる場合
            else if($total_remaining_holiday_days >= $sub_acquired_paid_leave_days){
                $total_remaining_holiday_days -= $sub_acquired_paid_leave_days;
                $sum_acquired_paid_leave_days += $sub_acquired_paid_leave_days;
                $sub_acquired_paid_leave_days = 0;
            }
            //残数足りない場合
            else{
                $sub_acquired_paid_leave_days -= $total_remaining_holiday_days;
                $sum_acquired_paid_leave_days += $total_remaining_holiday_days;
                $total_remaining_holiday_days = 0;
            }
            $remaining_holiday_days = floor($total_remaining_holiday_days);
            $remaining_holiday_half_days = ($total_remaining_holiday_days - $remaining_holiday_days) * 2;

            //取得数算出
            $acquired_holiday_days = floor($holidays->acquired_holiday_days + $holidays->acquired_holiday_half_days /2 + $sum_acquired_paid_leave_days);
            $acquired_holiday_half_days = ($holidays->acquired_holiday_days + $holidays->acquired_holiday_half_days /2 + $sum_acquired_paid_leave_days - $acquired_holiday_days) * 2;

            DB::table($this->table)
                ->where('holiday_management_id', $holidays->holiday_management_id)
                ->where('is_delete', 0)
                ->update(['acquired_holiday_days' => $acquired_holiday_days, 'acquired_holiday_half_days' => $acquired_holiday_half_days, 'remaining_holiday_days' => $remaining_holiday_days, 'remaining_holiday_half_days' => $remaining_holiday_half_days]);
        }
        return 0;
    }

    /**
     * 有効保存休情報取得
     */
    public function getValidUnusedAccumulatedHolidays($employee_id, $lastday_of_month)
    {
        $total_unused_accumulated_holiday_days = 0;
        $validUnusedAccumulatedHolidays = DB::table($this->table)
            ->select('remaining_holiday_days','remaining_holiday_half_days')
            ->where('employee_id', $employee_id)
            ->where('holiday_id', 2)
            ->where('valid_date_start', '<=', $lastday_of_month)
            ->where('valid_date_end', '>=', $lastday_of_month)
            ->where('is_delete', 0)
            ->get();

        foreach($validUnusedAccumulatedHolidays as $valid_holiday_info){
            //有休残数を計算
            $total_unused_accumulated_holiday_days = $valid_holiday_info->remaining_holiday_days + ($valid_holiday_info->remaining_holiday_half_days / 2);
        }
        return $total_unused_accumulated_holiday_days;
    }

    /**
     * 休暇情報全取得年間
     */
    public function getTargetHolidaysWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->whereBetween('grant_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->orderby('grant_date', 'asc')
            ->get();
    }

    /**
     * 期間内休暇付与日数取得
     */
    public function getGrantHolidayDays($employee_id,$grant_date,$holiday_id)
    {
        $grant_holiday = DB::table($this->table)
            ->select('grant_holiday_days')
            ->where('employee_id', $employee_id)
            ->where('holiday_id', $holiday_id)
            ->where('grant_date', $grant_date)
            ->where('is_delete', 0)
            ->first();
            return $grant_holiday->grant_holiday_days;
    }

    /**
     * 「付与日数」「使用済み日数」更新
     */
    public function updateHoliday($holiday_management_id,$grant_holiday_days,$acquired_holiday_days)
    {
        $remaining_holiday_days = $grant_holiday_days - $acquired_holiday_days;
        return DB::table($this->table)
            ->where('holiday_management_id', $holiday_management_id)
            ->where('is_delete', 0)
            ->update(['grant_holiday_days' => $grant_holiday_days,'acquired_holiday_days' => $acquired_holiday_days,'remaining_holiday_days' => $remaining_holiday_days]);
    }

    /**
     * 休暇情報新規
     */
    public function insertHoliday($holiday_id,$employee_id,$grant_date,$grant_holiday_days,$valid_date_end,$detail_no,$date,$employee_code)
    {
        return DB::table($this->table)
        ->insert([
            'holiday_id' => $holiday_id,
            'employee_id' => $employee_id,
            'grant_date' => $grant_date,
            'next_grant_paid_leave_date' => 0,
            'grant_holiday_days' => $grant_holiday_days,
            'acquired_holiday_days' => 0,
            'acquired_holiday_half_days' => 0,
            'acquired_holiday_time' => 0,
            'remaining_holiday_days' => $grant_holiday_days,
            'remaining_holiday_half_days' => 0,
            'remaining_holiday_time' => 0,
            'valid_date_start' => $grant_date,
            'valid_date_end' => $valid_date_end,
            'accumulated_paid_leave_transition_class' => 0,
            'priority_use_class' => 0,
            'obligatory_take_paid_leave_days' => 0,
            'detail_no' => $detail_no,
            'is_delete' => 0,
            'created_user' => $employee_code,
            'created_at' => $date, 
            'updated_user' => $employee_code,
            'updated_at' => $date,
        ]);
    }

    /**
     * データ数を取得
     */
    public function countHoliday()        
    {
        return DB::table($this->table)
            ->count();
    }

    /**
     * 全休暇情報取得
     */
    public function getAllHolidays()
    {
        return DB::table($this->table)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 休暇情報新規
     */
    public function uploadHoliday($holiday_id,$employee_id,$valid_date_start,$next_grant_paid_leave_date,$grant_holiday_days,$valid_date_end,$accumulated_paid_leave_transition_class,$obligatory_take_paid_leave_days,$detail_no,$user,$inputDate)
    {
        return DB::table($this->table)
        ->insert([
            'holiday_id' => $holiday_id,
            'employee_id' => $employee_id,
            'grant_date' => $valid_date_start,
            'next_grant_paid_leave_date' => $next_grant_paid_leave_date,
            'grant_holiday_days' => $grant_holiday_days,
            'acquired_holiday_days' => 0,
            'acquired_holiday_half_days' => 0,
            'acquired_holiday_time' => 0,
            'remaining_holiday_days' => $grant_holiday_days,
            'remaining_holiday_half_days' => 0,
            'remaining_holiday_time' => 0,
            'valid_date_start' => $valid_date_start,
            'valid_date_end' => $valid_date_end,
            'accumulated_paid_leave_transition_class' => $accumulated_paid_leave_transition_class,
            'priority_use_class' => 0,
            'obligatory_take_paid_leave_days' => $obligatory_take_paid_leave_days,
            'detail_no' => $detail_no,
            'is_delete' => 0,
            'created_user' => $user,
            'created_at' => $inputDate, 
            'updated_user' => $user,
            'updated_at' => $inputDate,
        ]);
    }
}
