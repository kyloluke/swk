<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\AppLibs\CommonFunctions;
class t003_attendance_aggregate extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't003_attendance_aggregate';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_year_month',
        'scheduled_working_days',
        'actual_working_days',
        'holiday_working_days',
        'actual_working_time',
        'statutory_working_time',
        'non_statutory_working_time',
        'deduction_time',
        'holiday_work_time',
        'midnight_time',
        'over_60hours',
        'last_grant_paid_leave_pattern_id',
        'acquired_paid_leave_days',
        'acquired_paid_leave_half_days',
        'acquired_paid_leave_time',
        'remaining_paid_leave_days',
        'remaining_paid_leave_half_days',
        'remaining_paid_leave_time',
        'paid_late_early_leave',
        'early_leave_late_arrival_days',
        'early_leave_late_arrival_days_absent',
        'special_paid_holiday_days',
        'special_paid_holiday_half_days',
        'special_paid_holiday_time',
        'special_non_paid_holiday_days',
        'special_non_paid_holiday_half_days',
        'special_non_paid_holiday_time',
        'accumulated_paid_leave_days',
        'accumulated_paid_leave_half_days',
        'accumulated_paid_leave_time',
        'unused_accumulated_paid_leave_days',
        'unused_accumulated_paid_leave_half_days',
        'unused_accumulated_paid_leave_time',
        'acquired_substitute_holidays',
        'absent_days',
        'absent_half_days',
        'absent_time',
        'close_state_id',
        'close_employee_id',
        'close_manager_employee_id',
        'thirtysix_agreement_special_provisions_apply_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "attendance_aggregate_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function last_grant_paid_leave_pattern()
    {
        return $this->belongsTo('App\Models\m033_grant_paid_leave_pattern', 'last_grant_paid_leave_pattern_id');
    }
    public function close_state()
    {
        return $this->belongsTo('App\Models\m019_close_state', 'close_state_id');
    }
    public function getData()
    {
        $t003AttendanceAggregate = DB::table($this->table)->get();

        return $t003AttendanceAggregate;
    }

    /**
     * 対象社員の指定期間内の集計テーブルを取得
     */
    public function getAttendanceAggregateWithinTerm($employee_id, $attendance_year_month)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->first();
    }

    /**
     * 指定期間内の集計テーブルを取得
     */
    public function getAttendanceAggregateByTerm($attendance_year_month)
    {
        return DB::table($this->table)
            ->select('employee_id', 'attendance_year_month','scheduled_working_days','actual_working_days','holiday_working_days','actual_working_time','statutory_working_time','non_statutory_working_time','deduction_time','holiday_work_time','midnight_time','over_60hours','last_grant_paid_leave_pattern_id','acquired_paid_leave_days', 'acquired_paid_leave_half_days', 'remaining_paid_leave_days','remaining_paid_leave_half_days','paid_late_early_leave','early_leave_late_arrival_days','early_leave_late_arrival_days_absent','special_paid_holiday_days','special_non_paid_holiday_days','accumulated_paid_leave_days', 'accumulated_paid_leave_half_days', 'unused_accumulated_paid_leave_days','unused_accumulated_paid_leave_half_days','acquired_substitute_holidays','absent_days','absent_time','close_state_id')
            ->where('attendance_year_month', $attendance_year_month)
            ->get();
    }

    /**
     * 指定期間内の集計情報を取得
     */
    public function getAllAttendanceAggregateByTerm($selectedStartMonth, $selectedEndMonth)
    {
        return DB::table($this->table)
            ->whereBetween('attendance_year_month', [$selectedStartMonth, $selectedEndMonth])
            ->where('is_delete', 0)
            ->orderBy('attendance_year_month', 'asc')
            ->get();
    }

    /**
     * 対象事業所の指定期間内の集計テーブルを取得
     */
    public function getAttendanceAggregateTargetOffice($office_id, $attendance_year_month)
    {
        return DB::table($this->table)
            ->select('attendance_year_month','scheduled_working_days','actual_working_days','holiday_working_days','actual_working_time','statutory_working_time','non_statutory_working_time','deduction_time','holiday_work_time','midnight_time','over_60hours','last_grant_paid_leave_pattern_id','acquired_paid_leave_days','acquired_paid_leave_half_days','acquired_paid_leave_time','remaining_paid_leave_days','remaining_paid_leave_half_days','remaining_paid_leave_time','early_leave_late_arrival_days','early_leave_late_arrival_days_absent','special_paid_holiday_days','special_paid_holiday_half_days','special_paid_holiday_time','special_non_paid_holiday_days','special_non_paid_holiday_half_days','special_non_paid_holiday_time','accumulated_paid_leave_days','accumulated_paid_leave_half_days','accumulated_paid_leave_time','unused_accumulated_paid_leave_days','unused_accumulated_paid_leave_half_days','unused_accumulated_paid_leave_time','acquired_substitute_holidays','absent_days','absent_time','close_state_id')
            ->where('office_id', $office_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->get();
    }

    public function createAttendanceAggregate($employee_id, $attendance_year_month)
    {
        DB::table($this->table)
            ->updateOrInsert(
                ['employee_id' => $employee_id, 'attendance_year_month' => $attendance_year_month],
                ['employee_id' => $employee_id, 'attendance_year_month' => $attendance_year_month, 'close_state_id' => 1]
            );
    }
    /**
     * 対象社員の締め状態ID更新
     */
    public function updateCloseStateId($employee_id, $attendance_year_month, $close_state_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->update(['close_state_id' => $close_state_id]);
    }
    /**
     * 対象社員,指定期間の締め状態を取得
     */
    public function getcloseStateId($employee_id, $attendance_year_month)
    {
        return DB::table($this->table)
            ->select('close_state_id')
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->first();
    }
    /**
     * 本人締め実施者ID更新
     */
    public function updateCloseEmployeeId($employee_id, $login_employee_id, $attendance_year_month)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->update(['close_employee_id' => $login_employee_id]);
    }
    /**
     * 管理者締め実施者ID更新
     */
    public function updateCloseManagerEmployeeId($employee_id, $login_employee_id, $attendance_year_month)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->update(['close_manager_employee_id' => $login_employee_id]);
    }

    /**
     * 集計情報の更新
     */
    public function applyAttendanceAggregate($employee_id, $first_paid_leave_date, $attendance_year_month,$last_month_attendance_aggregate,$attendance_aggregate,$unemployedList,$holiday_summary_info,$unemployed_info,$thirtysix_agreement_apply_id,$max_time_year,$holiday_work_time, $attendance_aggregate_remaining_paid_leave_days, $attendance_aggregate_unused_accumulated_paid_leave_days)
    {
        $cf = new CommonFunctions();

        $attendance_aggregate_actual_working_days = 0;
        $attendance_aggregate_holiday_working_days = 0;
        $attendance_aggregate_actual_working_time = 0;
        $attendance_aggregate_statutory_working_time = 0;
        $attendance_aggregate_non_statutory_working_time = 0;
        $attendance_aggregate_deduction_time = 0;
        $attendance_aggregate_holiday_work_time = 0;
        $attendance_aggregate_midnight_time = 0;
        $attendance_aggregate_over_60hours = 0;
        $attendance_aggregate_acquired_paid_leave_days = 0;
        $attendance_aggregate_acquired_paid_leave_half_days = 0;
        $attendance_aggregate_acquired_paid_leave_time = 0;
        $attendance_aggregate_remaining_paid_leave_half_days = 0;
        $attendance_aggregate_early_leave_late_arrival_days = 0;
        $attendance_aggregate_early_leave_late_arrival_days_absent = 0;
        $attendance_aggregate_special_paid_holiday_days = 0;
        $attendance_aggregate_special_paid_holiday_half_days = 0;
        $attendance_aggregate_special_paid_holiday_time = 0;
        $attendance_aggregate_special_non_paid_holiday_days = 0;
        $attendance_aggregate_special_non_paid_holiday_half_days = 0;
        $attendance_aggregate_special_non_paid_holiday_time = 0;
        $attendance_aggregate_accumulated_paid_leave_days = 0;
        $attendance_aggregate_accumulated_paid_leave_half_days = 0;
        $attendance_aggregate_accumulated_paid_leave_time = 0;
        $attendance_aggregate_unused_accumulated_paid_leave_half_days = 0;
        $attendance_aggregate_acquired_substitute_holidays = 0;
        $attendance_aggregate_absent_days = 0;
        $attendance_aggregate_absent_half_days = 0;
        $attendance_aggregate_absent_time = 0; 
        $attendance_aggregate_paid_late_early_leave = 0;
        $attendance_aggregate_thirtysix_agreement_special_provisions_apply_class = 0;
        $attendance_aggregate_scheduled_working_days = 0;

        $acquiredList = array();
        $accumulatedList = array();
        $specialList = array();
        $earlyLeaveLateArrivalList = array();
        $absenceList = array();

        foreach($holiday_summary_info as $holidaySummaryInfo){
            if($holidaySummaryInfo->holiday_id == 1 || $holidaySummaryInfo->holiday_id == 3){
                $acquiredList[] = array(
                    'unemployed_id' => $holidaySummaryInfo->unemployed_id,
                );
            }else if($holidaySummaryInfo->holiday_id == 2){
                $accumulatedList[] = array(
                    'unemployed_id' => $holidaySummaryInfo->unemployed_id,
                );
            }else if($holidaySummaryInfo->holiday_id == 7){
                $specialList[] = array(
                    'unemployed_id' => $holidaySummaryInfo->unemployed_id,
                );
            }else if($holidaySummaryInfo->holiday_id == 8){
                $earlyLeaveLateArrivalList[] = array(
                    'unemployed_id' => $holidaySummaryInfo->unemployed_id,
                );
            }
        }
        foreach($unemployed_info as $unemployedInfo){
            if($unemployedInfo['work_day_target_class'] == 0){
                $absenceList[] = array(
                    'unemployed_id' => $unemployedInfo['unemployed_id'],
                );
            }
        }
        $acquired_array = array_column($acquiredList, 'unemployed_id');
        $accumulated_array = array_column($accumulatedList, 'unemployed_id');
        $special_array = array_column($specialList, 'unemployed_id');
        $early_leave_late_arrival_array = array_column($earlyLeaveLateArrivalList, 'unemployed_id');
        $absence_array = array_column($absenceList, 'unemployed_id');

        foreach($attendance_aggregate as $info){
            //実働日数
            if($info->actual_work_time > 0){
                $attendance_aggregate_actual_working_days += 1;
            }
            //休日出勤日数
            if($info->work_achievement_id > 3 && $info->work_achievement_id < 8){
                $attendance_aggregate_holiday_working_days += 1;
            }
            //実労働時間
            $attendance_aggregate_actual_working_time += $info->actual_work_time;
            //法定内時間外時間
            $attendance_aggregate_statutory_working_time += $info->statutory_working_time;
            //法定外時間外時間
            $attendance_aggregate_non_statutory_working_time += $info->non_statutory_working_time;
            //控除時間
            $attendance_aggregate_deduction_time += $info->deduction_time;
            //休日勤務時間
            if($info->work_achievement_id > 3 || $info->work_achievement_id < 8){
                $attendance_aggregate_holiday_work_time += ($info->holiday_work_time + $info->holiday_midnight_work_time);
            }
            //深夜時間
            $attendance_aggregate_midnight_time += ($info->midnight_time + $info->holiday_midnight_work_time);
            //60時間超過・所定就業日数
            if($info->work_holiday_id == 1){
                $attendance_aggregate_over_60hours += $info->statutory_working_time + $info->non_statutory_working_time;
                $attendance_aggregate_scheduled_working_days += 1;
            }else if($info->work_holiday_id == 2){
                $attendance_aggregate_over_60hours += $info->holiday_work_time + $info->holiday_midnight_work_time;
            }
            //振替休日取得日数
            if($info->work_achievement_id == 9){
                $attendance_aggregate_acquired_substitute_holidays += 1;
            }
            //欠勤時間
            $attendance_aggregate_absent_time += $info->absent_time;

        }
        //60時間超過計算
        if($attendance_aggregate_over_60hours > 3600){
            $attendance_aggregate_over_60hours -=3600;
        }else{
            $attendance_aggregate_over_60hours = 0;
        }

        //有休付与月に有休遅刻・有休早退の回数リセット（シヤチハタ内規処置）
        $month = intval($cf->serialToMonth($first_paid_leave_date));
        $attendance_month = intval(substr($attendance_year_month, -2));
        if($attendance_month == $month || !$last_month_attendance_aggregate){
            $attendance_aggregate_paid_late_early_leave = 0;
        }
        else{
            $attendance_aggregate_paid_late_early_leave = $last_month_attendance_aggregate->paid_late_early_leave;
        }

        foreach($unemployedList as $info){
            //欠勤日数
            if(in_array($info['unemployed_id'], $absence_array)){
                if($info['unemployed_info']->unemployed_take_unit_class == 1){
                    //欠勤日数(日数単位)
                    $attendance_aggregate_absent_days += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                    //欠勤日数(半日数単位)
                    $attendance_aggregate_absent_half_days += 1;
                }
            }
            //有給取得日数
            if(in_array($info['unemployed_id'], $acquired_array)){
                if($info['unemployed_info']->unemployed_take_unit_class == 1){
                    //有給取得日数(日数単位)
                    $attendance_aggregate_acquired_paid_leave_days += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                    //有給取得日数(半日数単位)
                    $attendance_aggregate_acquired_paid_leave_half_days += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 3){
                    //有給取得日数(時間数単位)
                    $attendance_aggregate_acquired_paid_leave_time += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 4){ //有休遅刻・有休早退のシヤチハタ内規処置
                    $attendance_aggregate_paid_late_early_leave += 1;
                    $remainder_paid_late_early_leave = $attendance_aggregate_paid_late_early_leave % 3;
                    if($remainder_paid_late_early_leave == 0 || $remainder_paid_late_early_leave == 2){ //3回単位で2回目と3回目で有休半日取得
                        //有給取得日数(半日数単位)
                        $attendance_aggregate_acquired_paid_leave_half_days += 1;
                    }
                }
            }
            //保存休取得日数
            if(in_array($info['unemployed_id'], $accumulated_array)){
                if($info['unemployed_info']->unemployed_take_unit_class == 1){
                    //保存休取得日数(日数単位)
                    $attendance_aggregate_accumulated_paid_leave_days += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                    //保存休取得日数(半日数単位)
                    $attendance_aggregate_accumulated_paid_leave_half_days += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 3){
                    //保存休取得日数(時間数単位)
                    $attendance_aggregate_accumulated_paid_leave_time += 1;
                }
            }
            //特別休暇日数
            if(in_array($info['unemployed_id'], $special_array)){
                if($info['unemployed_info']->paid_leave_target_class == 0){
                    //特別休暇日数(無給)
                    if($info['unemployed_info']->unemployed_take_unit_class == 1){
                        //特別休暇日数(日数単位)(無給)
                        $attendance_aggregate_special_non_paid_holiday_days += 1;
                    }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                        //特別休暇日数(半日数単位)(無給)
                        $attendance_aggregate_special_non_paid_holiday_half_days += 1;
                    }else if($info['unemployed_info']->unemployed_take_unit_class == 3){
                        //特別休暇日数(時間数単位)(無給)
                        $attendance_aggregate_special_non_paid_holiday_time += 1;
                    } 
                }else if($info['unemployed_info']->paid_leave_target_class == 1){
                    //特別休暇日数(有給)
                    if($info['unemployed_info']->unemployed_take_unit_class == 1){
                        //特別休暇日数(日数単位)(有給)
                        $attendance_aggregate_special_paid_holiday_days += 1;
                    }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                        //特別休暇日数(半日数単位)(有給)
                        $attendance_aggregate_special_paid_holiday_half_days += 1;
                    }else if($info['unemployed_info']->unemployed_take_unit_class == 3){
                        //特別休暇日数(時間数単位)(有給)
                        $attendance_aggregate_special_paid_holiday_time += 1;
                    } 
                }
            }
            //遅早回数
            if(in_array($info['unemployed_id'], $early_leave_late_arrival_array)){
                if($info['unemployed_info']->paid_leave_target_class == 1){
                    //遅早回数(有給)
                    if($info['unemployed_info']->unemployed_take_unit_class == 1){
                        //遅早回数(有給、日数単位)
                        $attendance_aggregate_early_leave_late_arrival_days += 2;
                    }else{
                        //遅早回数(有給、半日数単位＆時間単位)
                        $attendance_aggregate_early_leave_late_arrival_days += 1;
                    }
                }else if($info['unemployed_info']->paid_leave_target_class == 0){
                    //遅早回数(無給)
                    if($info['unemployed_info']->unemployed_take_unit_class == 1){
                        //遅早回数(無給、日数単位)
                        $attendance_aggregate_early_leave_late_arrival_days_absent += 2;
                    }else{
                        //遅早回数(無給、半日数単位＆時間単位)
                        $attendance_aggregate_early_leave_late_arrival_days_absent += 1;
                    }
                }
            }
        }
        //有休の半日数を分離
        if(!preg_match('/^[0-9]+$/', $attendance_aggregate_remaining_paid_leave_days)) {
            $attendance_aggregate_remaining_paid_leave_days = floor($attendance_aggregate_remaining_paid_leave_days);
            $attendance_aggregate_remaining_paid_leave_half_days = 1;
        }
        //保存休の半日数を分離
        if(!preg_match('/^[0-9]+$/', $attendance_aggregate_unused_accumulated_paid_leave_days)) {
            $attendance_aggregate_unused_accumulated_paid_leave_days = floor($attendance_aggregate_unused_accumulated_paid_leave_days);
            $attendance_aggregate_unused_accumulated_paid_leave_half_days = 1;
        }

        //36協定特別条項適用区分
        if($thirtysix_agreement_apply_id == 2 && ($attendance_aggregate_non_statutory_working_time + $holiday_work_time) > $max_time_year){
            $attendance_aggregate_thirtysix_agreement_special_provisions_apply_class = 1;
        }

        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $attendance_year_month)
            ->update([
                'actual_working_days' =>  $attendance_aggregate_actual_working_days ,
                'holiday_working_days' =>  $attendance_aggregate_holiday_working_days ,
                'actual_working_time' =>  $attendance_aggregate_actual_working_time ,
                'statutory_working_time' =>  $attendance_aggregate_statutory_working_time ,
                'non_statutory_working_time' =>  $attendance_aggregate_non_statutory_working_time ,
                'deduction_time' =>  $attendance_aggregate_deduction_time ,
                'holiday_work_time' =>  $attendance_aggregate_holiday_work_time ,
                'midnight_time' =>  $attendance_aggregate_midnight_time ,
                'over_60hours' =>  $attendance_aggregate_over_60hours ,
                'acquired_paid_leave_days' =>  $attendance_aggregate_acquired_paid_leave_days ,
                'acquired_paid_leave_half_days' =>  $attendance_aggregate_acquired_paid_leave_half_days ,
                'acquired_paid_leave_time' =>  $attendance_aggregate_acquired_paid_leave_time ,
                'remaining_paid_leave_days' =>  $attendance_aggregate_remaining_paid_leave_days ,
                'remaining_paid_leave_half_days' =>  $attendance_aggregate_remaining_paid_leave_half_days ,
                //'remaining_paid_leave_time' =>  $attendance_aggregate_remaining_paid_leave_time ,
                'paid_late_early_leave' =>  $attendance_aggregate_paid_late_early_leave ,
                'early_leave_late_arrival_days' =>  $attendance_aggregate_early_leave_late_arrival_days ,
                'early_leave_late_arrival_days_absent' =>  $attendance_aggregate_early_leave_late_arrival_days_absent ,
                'special_paid_holiday_days' =>  $attendance_aggregate_special_paid_holiday_days ,
                'special_paid_holiday_half_days' =>  $attendance_aggregate_special_paid_holiday_half_days ,
                'special_paid_holiday_time' =>  $attendance_aggregate_special_paid_holiday_time ,
                'special_non_paid_holiday_days' =>  $attendance_aggregate_special_non_paid_holiday_days ,
                'special_non_paid_holiday_half_days' =>  $attendance_aggregate_special_non_paid_holiday_half_days ,
                'special_non_paid_holiday_time' =>  $attendance_aggregate_special_non_paid_holiday_time ,
                'accumulated_paid_leave_days' =>  $attendance_aggregate_accumulated_paid_leave_days ,
                'accumulated_paid_leave_half_days' =>  $attendance_aggregate_accumulated_paid_leave_half_days ,
                'accumulated_paid_leave_time' =>  $attendance_aggregate_accumulated_paid_leave_time ,
                'unused_accumulated_paid_leave_days' =>  $attendance_aggregate_unused_accumulated_paid_leave_days ,
                'unused_accumulated_paid_leave_half_days' =>  $attendance_aggregate_unused_accumulated_paid_leave_half_days ,
                //'unused_accumulated_paid_leave_time' =>  $attendance_aggregate_unused_accumulated_paid_leave_time ,
                'acquired_substitute_holidays' =>  $attendance_aggregate_acquired_substitute_holidays ,
                'absent_days' =>  $attendance_aggregate_absent_days ,
                'absent_half_days' =>  $attendance_aggregate_absent_half_days ,
                'absent_time' =>  $attendance_aggregate_absent_time, 
                'thirtysix_agreement_special_provisions_apply_class' => $attendance_aggregate_thirtysix_agreement_special_provisions_apply_class,
                'scheduled_working_days' => $attendance_aggregate_scheduled_working_days,
            ]
        );
    }
    /**
     * 実働時間と日数を更新
     */
    public function updateActualWork($employee_id, $year_month, $actual_work_time_sum, $actual_work_days)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $year_month)
            ->update([
                'actual_working_days' => $actual_work_days,
                'actual_working_time' => $actual_work_time_sum,
            ]);
    }

    public function updateRemainingPaidLeaveDays($employee_id,$year_month,$grant_holiday_days){

        $attendance_aggregate_info = $this->getAttendanceAggregateWithinTerm($employee_id, $year_month);

        if($attendance_aggregate_info != null){
            $remaining_paid_leave_days = $attendance_aggregate_info->remaining_paid_leave_days + $grant_holiday_days;
            return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $year_month)
            ->update([
                'remaining_paid_leave_days' => $remaining_paid_leave_days,
            ]);
        }else{
            return false;
        }
    }
    public function updateUnusedAccumulatedPaidLeaveDays($employee_id,$year_month,$grant_holiday_days){

        $attendance_aggregate_info = $this->getAttendanceAggregateWithinTerm($employee_id, $year_month);

        if($attendance_aggregate_info != null){
            $unused_accumulated_paid_leave_days = $attendance_aggregate_info->unused_accumulated_paid_leave_days + $grant_holiday_days;
            return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_year_month', $year_month)
            ->update([
                'unused_accumulated_paid_leave_days' => $unused_accumulated_paid_leave_days,
            ]);
        }else{
            return false;
        }
    }
}
