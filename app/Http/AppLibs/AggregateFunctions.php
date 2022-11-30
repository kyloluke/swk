<?php

namespace App\Http\AppLibs;

use App\Models\m007_employee;
use App\Models\m031_unemployed;
use App\Models\m034_36agreement;
use App\Models\m036_36agreement_max_time;
use App\Models\m043_holiday;
use App\Models\m044_holiday_summary;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t008_unemployed_information;
use App\Models\t009_holiday_management;
use App\Http\AppLibs\CommonFunctions;

class AggregateFunctions
{
    /**
     * T003集計
     */
    public static function aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term)
    {
        $cf = new CommonFunctions();
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $t008_unemployed_information = new t008_unemployed_information();
        $t009_holiday_management = new t009_holiday_management();
        $model_m034_36agreement = new m034_36agreement();
        $model_m036_36agreement_max_time = new m036_36agreement_max_time();

        //T003更新用データ取得
        //36協定適用ID取得
        $thirtysix_agreement_apply_id = m007_employee::find($employee->employee_id)->thirtysix_agreement_apply_id;
        //36協定ID
        $thirtysix_agreement_id = $model_m034_36agreement->getThirtysixAgreementData($thirtysix_agreement_apply_id, $target_start_serial)->thirtysix_agreement_id;

        //36協定集計単位区分ID
        $thirtysix_agreement_aggregate_class_id = 0;
        //特別条項適用回数
        $thirtysix_agreement_special_provisions_apply_class = 0;
        //上限時間
        $max_time_year = 0;

        if($thirtysix_agreement_apply_id == 2){
            $thirtysix_agreement_aggregate_class_id = 1;
            $thirtysix_agreement_special_provisions_apply_class = 1;
            $max_time_year = $model_m036_36agreement_max_time->getThirtysixAgreementId($thirtysix_agreement_id,$thirtysix_agreement_aggregate_class_id)->max_time;
        }

        //法定外休日出勤時間(残業時間加算)
        $start_week_start_time_serial = $target_start_serial - $cf->serialToWeek($target_start_serial);
        $end_week_start_time_serial = $target_end_serial - $cf->serialToWeek($target_end_serial);
        $week_no = ($end_week_start_time_serial - $start_week_start_time_serial) / 7;
        $holiday_work_time = 0;
        $actual_work_time_week = 0;
        $holiday_work_time_week = 0;
        for($i = 0 ; $i <= $week_no ; $i++){
            if($i == 0){
                if($t002_attendance_information->getActualWorkTimeWithinTerm($employee->employee_id, $start_week_start_time_serial, $target_start_serial) <= 40 * 60){
                    $actual_work_time_week = $t002_attendance_information->getActualWorkTimeWithinTerm($employee->employee_id, $start_week_start_time_serial, $start_week_start_time_serial + 6) - 40 * 60;
                    if($actual_work_time_week <= 0){
                        continue;
                    }
                    $holiday_work_time_week = $t002_attendance_information->getHolidayWorkTimeWithinTerm($employee->employee_id, $start_week_start_time_serial, $start_week_start_time_serial + 6);
                    if($holiday_work_time_week >= $actual_work_time_week){
                        $holiday_work_time += $actual_work_time_week;
                    }else{
                        $holiday_work_time += $holiday_work_time_week;
                    }
                }
            }else if($i == $week_no){
                if($t002_attendance_information->getActualWorkTimeWithinTerm($employee->employee_id, $end_week_start_time_serial, $target_end_serial) > 40 * 60){
                    $actual_work_time_week = $t002_attendance_information->getActualWorkTimeWithinTerm($employee->employee_id, $end_week_start_time_serial, $end_week_start_time_serial + 6) - 40 * 60;
                    $holiday_work_time_week = $t002_attendance_information->getHolidayWorkTimeWithinTerm($employee->employee_id, $end_week_start_time_serial, $end_week_start_time_serial + 6);
                    if($holiday_work_time_week >= $actual_work_time_week){
                        $holiday_work_time += $actual_work_time_week;
                    }else{
                        $holiday_work_time += $holiday_work_time_week;
                    }
                }
            }else{
                if($t002_attendance_information->getActualWorkTimeWithinTerm($employee->employee_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) > 40 * 60){
                    $actual_work_time_week = $t002_attendance_information->getActualWorkTimeWithinTerm($employee->employee_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) - 40 * 60;
                    $holiday_work_time_week = $t002_attendance_information->getHolidayWorkTimeWithinTerm($employee->employee_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6);
                    if($holiday_work_time_week >= $actual_work_time_week){
                        $holiday_work_time += $actual_work_time_week;
                    }else{
                        $holiday_work_time += $holiday_work_time_week;
                    }
                }
            }
        }
        
        $t002_attendance_info = $t002_attendance_information->getAttendanceInformationWithinTerm($employee->employee_id, $target_start_serial, $target_end_serial);
        $t008_unemployed_info = $t008_unemployed_information->getUnemployedInformationWithinTerm($employee->employee_id, $target_start_serial, $target_end_serial);
        $unemployedList = array();
        //不就業がある場合、条件取得
        foreach($t008_unemployed_info as $unemployed_info)
        {
            $unemployedList[] = array(
                'unemployed_information_id' => $unemployed_info->unemployed_information_id,
                'unemployed_id' => $unemployed_info->unemployed_id,
                'target_date' => $unemployed_info->target_date,
                'unemployed_info' => m031_unemployed::find($unemployed_info->unemployed_id),
            );
        }

        //各有休の有効開始日、終了日、有休残数を取得
        $t009_ValidHolidaysList = $t009_holiday_management->getValidHolidays($employee->employee_id, $target_start_serial, $target_end_serial);

        //保存休残数と半日保存休残数の合計を取得
        $t009_total_unused_accumulated_paid_leave_days = $t009_holiday_management->getValidUnusedAccumulatedHolidays($employee->employee_id, $target_end_serial);

        //有休、保存休、特休、遅早
        $m044_holiday_summary_model = new m044_holiday_summary();
        $m044_holiday_summary_info = $m044_holiday_summary_model->getData();

        //不就業
        $m031_unemployed = new m031_unemployed();
        $m031_unemployed_info = $m031_unemployed->getData();

        //先月
        $last_month = $cf->calcYearMonth($target_term, -1);
        $last_month_t003_attendance_aggregate_info = $t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee->employee_id, $last_month);

        $attendance_aggregate_remaining_paid_leave_days = 0;
        $attendance_aggregate_unused_accumulated_paid_leave_days = 0;
        $attendance_aggregate_accumulated_paid_leave_days = 0;

        $acquiredList = array();
        $accumulatedList = array();

        //有休使用日
        $acquiredPaidLeaveDaysList = array();

        foreach($m044_holiday_summary_info as $holidaySummaryInfo){
            if($holidaySummaryInfo->holiday_id == 1){
                $acquiredList[] = array(
                    'unemployed_id' => $holidaySummaryInfo->unemployed_id,
                );
            }else if($holidaySummaryInfo->holiday_id == 2){
                $accumulatedList[] = array(
                    'unemployed_id' => $holidaySummaryInfo->unemployed_id,
                );
            }
        }
        $acquired_array = array_column($acquiredList, 'unemployed_id');
        $accumulated_array = array_column($accumulatedList, 'unemployed_id');
        foreach($unemployedList as $info){
            //有給取得日数
            if(in_array($info['unemployed_id'], $acquired_array)){
                if($info['unemployed_info']->unemployed_take_unit_class == 1){
                    //有給取得日数(日数単位)
                    $acquiredPaidLeaveDaysList[] = [
                        'target_date' => $info['unemployed_info']->target_date, 
                        'days_of_use' => 1, 
                    ];
                }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                    //有給取得日数(半日数単位)
                    $acquiredPaidLeaveDaysList[] = [
                        'target_date' => $info['unemployed_info']->target_date, 
                        'days_of_use' => 0.5, 
                    ];
                }
            }
            //保存休取得日数
            if(in_array($info['unemployed_id'], $accumulated_array)){
                if($info['unemployed_info']->unemployed_take_unit_class == 1){
                    //保存休取得日数(日数単位)
                    $attendance_aggregate_accumulated_paid_leave_days += 1;
                }else if($info['unemployed_info']->unemployed_take_unit_class == 2){
                    //保存休取得日数(半日数単位)
                    $attendance_aggregate_accumulated_paid_leave_days += 0.5;
                }
            }
        }

        //有休使用日を回す
        foreach($acquiredPaidLeaveDaysList as $acquiredPaidLeave){
            //各有休情報を回す
            $day_of_use = $acquiredPaidLeave['days_of_use'];
            for($i = 0; $i < count($t009_ValidHolidaysList); $i++){
                //残数が0なら次の有休に
                if($t009_ValidHolidaysList[$i]['total_remaining_holiday_days'] == 0){
                    continue;
                }
                //有休の使用日が有休有効期限内である場合
                if($acquiredPaidLeave['target_date'] <= $t009_ValidHolidaysList[$i]['valid_date_end']){
                    if($t009_ValidHolidaysList[$i]['total_remaining_holiday_days'] < $day_of_use){
                        $day_of_use -= $t009_ValidHolidaysList[$i]['total_remaining_holiday_days'];
                        $valid_holiday_info['total_remaining_holiday_days'] = 0;
                        continue;
                    }
                    else
                    {

                    }
                    $t009_ValidHolidaysList[$i]['total_remaining_holiday_days'] -= $day_of_use;
                    $day_of_use = 0;
                    break;
                }
            }
        }
        //有休残日数
        foreach($t009_ValidHolidaysList as $valid_holiday_info){
            //有休残数以上の申請があった場合
            if($valid_holiday_info['total_remaining_holiday_days'] < 0){
                return "申請可能な有休の日数を超えて申請されています。";
            }
            //有効期限が切れていないものだけ集計
            if($valid_holiday_info['valid_date_end'] >= $target_end_serial){
                $attendance_aggregate_remaining_paid_leave_days += $valid_holiday_info['total_remaining_holiday_days'];
            }
        }
        //保存休残日数
        $attendance_aggregate_unused_accumulated_paid_leave_days = $t009_total_unused_accumulated_paid_leave_days - $attendance_aggregate_accumulated_paid_leave_days;

        //T003を更新する
        $t003_attendance_aggregate->applyAttendanceAggregate($employee->employee_id, $employee->first_paid_leave_date, $target_term, $last_month_t003_attendance_aggregate_info, $t002_attendance_info,$unemployedList,$m044_holiday_summary_info,$m031_unemployed_info,$thirtysix_agreement_apply_id,$max_time_year,$holiday_work_time, $attendance_aggregate_remaining_paid_leave_days, $attendance_aggregate_unused_accumulated_paid_leave_days);
        return true;
    }
}