<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m024_work_zone_time;
use App\Models\m027_work_holiday;
use App\Models\m028_web_punch_clock_deviation_time;
use App\Models\m029_theme;
use App\Models\m030_work_achievement;
use App\Models\m031_unemployed;
use App\Models\m033_grant_paid_leave_pattern;
use App\Models\m043_holiday;
use App\Models\m034_36agreement;
use App\Models\m036_36agreement_max_time;
use App\Models\m046_grant_paid_leave_type;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t007_over_time_achievement;
use App\Models\t008_unemployed_information;
use App\Models\t009_holiday_management;
use App\Models\t010_acquired_holiday;
use App\Models\t017_daily_report;
use App\Http\AppLibs\CommonFunctions;

class InputAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getDailyReportInfoList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //データ取得
        $model_m007_employee = new m007_employee();
        $model_t017_daily_report = new t017_daily_report();
        $selectedEmployeeId = array();
        $t017_daily_report_info = array();
        //年月日（requestから対象年月日取得）
        $firstday_serial = $request->input('firstday_serial');
        $lastday_serial = $request->input('lastday_serial');

        if(!$cf->checkDate($cf->serialToDate($firstday_serial)) || !$cf->checkDate($cf->serialToDate($lastday_serial))){
            return response()->json([
                'result' => false,
            ]);
        }

        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');

        $selectedEmployeeId = $request->input('selectedEmployeeIdList');
        
        $j = 0;
        if($selectedEmployeeId != null){
            if(count($selectedEmployeeId)==0){
                $t017_daily_report_info = $model_t017_daily_report->getDailyReportWithinTerm($employee_id, $firstday_serial, $lastday_serial);
            }else{
                for($i = 0 ; $i < count($selectedEmployeeId) ; $i++){
                    $employee_id = $selectedEmployeeId[$i];
                    foreach($model_t017_daily_report->getDailyReportWithinTerm($employee_id, $firstday_serial, $lastday_serial) as $employee){
                        //m007_employeeより社員名と社員コード取得
                        $name = m007_employee::find($employee->employee_id)->employee_name;
                        $code = m007_employee::find($employee->employee_id)->employee_code;
                        $employee->employee_name = $name;
                        $employee->employee_code = $code;
                        $t017_daily_report_info[$j] = $employee;
                        $j++;
                    }
                }
                // 第1ソートキー
                $employee_code  = array_column($t017_daily_report_info, 'employee_code');
                // 第2ソートキー
                $work_date = array_column($t017_daily_report_info, 'work_date');
                // 第3ソートキー
                $work_no = array_column($t017_daily_report_info, 'work_no');
                array_multisort($employee_code, SORT_ASC, $work_date, SORT_ASC, $work_no, SORT_ASC, $t017_daily_report_info);
            }
        }else{
            $t017_daily_report_info = $model_t017_daily_report->getDailyReportWithinTerm($employee_id, $firstday_serial, $lastday_serial);

        }
        //テーマIDに応じたテーマ名をセット
        $m029_theme = new m029_theme();
        $theme = $m029_theme->getData();
        
        for($i = 0; $i < count($t017_daily_report_info); $i++)
        {
            $theme_id = array_search($t017_daily_report_info[$i]->theme_id, array_column($theme->all(), 'theme_id'));
            $t017_daily_report_info[$i]->work_item_name = $theme_id === false ? '' : $theme[$theme_id]->theme;
        }
        return response()->json([
            'result' => true,
            't017_daily_report_info' => $t017_daily_report_info,
        ]);
    }

    /**
     * 勤怠入力画面のデータ取得
     */
    public function getInputAttendanceInfo(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');
        //検証
        if($employee_id == 0)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '不正なパラメータ指定です（001）',
                ]
            ]);
        }

        //年月（requestから対象年月取得）
        $target_term = $request->input('yearMonth');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '不正なパラメータ指定です（002）',
                ]
            ]);
        }

        //締め区分を取得
        $m007_employee = m007_employee::find($employee_id);
        $close_date_id = $m007_employee->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        $model_t001_web_punch_clock = new t001_web_punch_clock();
        //t002_勤怠データ取得
        $model_t002_attendance_information = new t002_attendance_information();
        $t002_attendance_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $target_start_serial, $target_end_serial);
 
        //乖離判定
        $model_m024_work_zone_time = new m024_work_zone_time();
        $model_m028_web_punch_clock_deviation_time = new m028_web_punch_clock_deviation_time();
        //出勤許容する時間取得
        $start_allow_time = $model_m028_web_punch_clock_deviation_time->getAllowTime($m007_employee->deviation_time_before_start_time_id);
        //退勤許容する時間取得
        $end_allow_time = $model_m028_web_punch_clock_deviation_time->getAllowTime($m007_employee->deviation_time_after_end_time_id);
        //乖離情報
        $violation_warning_array = array();
        //承認対象者名取得
        $employee_list = array(['employee_id' => -1, 'employee_name' => 'dummy']);
        //乖離情報
        $work_holiday_short_name_array = array();
        foreach($t002_attendance_info as $attendance_info)
        {
            $work_holiday_info = m027_work_holiday::find($attendance_info->work_holiday_id);
            $attendance_info->work_holiday_class = $work_holiday_info->work_holiday_class;
            if($work_holiday_info == null){
                $work_holiday_short_name_array[] = '';
            }else{
                $work_holiday_short_name_array[] = $work_holiday_info->work_holiday_short_name;
            }
            //対象者名
            if($attendance_info->input_employee_id != 0 && !in_array($attendance_info->input_employee_id, array_column($employee_list, 'employee_id'), true))
            {
                $employee_list = array_merge($employee_list, array(['employee_id' => $attendance_info->input_employee_id, 'employee_name' => m007_employee::find($attendance_info->input_employee_id)->employee_name]));
            }
            if($attendance_info->approval_employee_id != 0 && !in_array($attendance_info->approval_employee_id, array_column($employee_list, 'employee_id'), true))
            {
                $employee_list = array_merge($employee_list, array(['employee_id' => $attendance_info->approval_employee_id, 'employee_name' => m007_employee::find($attendance_info->approval_employee_id)->employee_name]));
            }
            //乖離状態
            $start_violation_flg = false;
            $end_violation_flg = false;

            if($attendance_info->attendance_date >= $cf->getTodaySerial()){
                $violation_warning_array[] = array(
                    'start_violation_flg' => $start_violation_flg,
                    'end_violation_flg' => $end_violation_flg,
                );
                continue;
            }
            //申請状態が初期状態の以外の場合は更新しない
            if($attendance_info->approval_state_id != 1)
            {
                $violation_warning_array[] = array(
                    'start_violation_flg' => $start_violation_flg,
                    'end_violation_flg' => $end_violation_flg,
                );
                continue;
            }
            if($attendance_info->work_holiday_id == 1){
                //勤務帯が登録されていない場合は判定しない
                if($attendance_info->work_zone_id == 0)
                {
                    $violation_warning_array[] = array(
                        'start_violation_flg' => $start_violation_flg,
                        'end_violation_flg' => $end_violation_flg,
                    );
                    continue;
                }
                //手入力打刻乖離
                if($model_t001_web_punch_clock->getInputDataWithinData($employee_id, $attendance_info->attendance_date, 1) != null){
                    $start_violation_flg = true;
                }
                if($model_t001_web_punch_clock->getInputDataWithinData($employee_id, $attendance_info->attendance_date, 2) != null){
                    $end_violation_flg = true;
                }

                if($start_violation_flg || $end_violation_flg){
                    $violation_warning_array[] = array(
                        'start_violation_flg' => $start_violation_flg,
                        'end_violation_flg' => $end_violation_flg,
                    );
                    continue;
                }

                //想定時間帯を取得(勤務時間：１)
                $start_end_time = $model_m024_work_zone_time->getStartEndTime($attendance_info->work_zone_id,1);

                if($start_allow_time->allow_before_start_time < $start_end_time->start_time - $attendance_info->web_punch_clock_time_start ||
                    $attendance_info->web_punch_clock_time_start - $start_end_time->start_time > $start_allow_time->allow_after_end_time){
                    //開始乖離あり
                    $start_violation_flg = true;
                }
                if($end_allow_time->allow_before_start_time < $start_end_time->end_time - $attendance_info->web_punch_clock_time_end ||
                    $attendance_info->web_punch_clock_time_end - $start_end_time->end_time > $end_allow_time->allow_after_end_time){
                    //終了乖離あり
                    $end_violation_flg = true;
                }
                $violation_warning_array[] = array(
                    'start_violation_flg' => $start_violation_flg,
                    'end_violation_flg' => $end_violation_flg,
                );
            }else if($attendance_info->work_holiday_id == 2 || $attendance_info->work_holiday_id == 3){
                //実績情報を取得
                $model_m030_work_achievement = new m030_work_achievement();
                $m030_work_achievement_data = $model_m030_work_achievement->getWorkAchievementDisplayClassByID($attendance_info->work_achievement_id);
                //休日勤務でない場合
                if($m030_work_achievement_data != null && $m030_work_achievement_data->work_achievement_display_class != 3 && $m030_work_achievement_data->work_achievement_display_class != 4){
                    //手入力打刻乖離
                    if($model_t001_web_punch_clock->getInputDataWithinData($employee_id, $attendance_info->attendance_date, 1) != null){
                        $start_violation_flg = true;
                    }
                    if($model_t001_web_punch_clock->getInputDataWithinData($employee_id, $attendance_info->attendance_date, 2) != null){
                        $end_violation_flg = true;
                    }
                    //打刻がある場合は乖離あり
                    if($attendance_info->web_punch_clock_time_start != null){
                        $start_violation_flg = true;
                    }
                    if($attendance_info->web_punch_clock_time_end != null){
                        $end_violation_flg = true;
                    }
                }
                $violation_warning_array[] = array(
                    'start_violation_flg' => $start_violation_flg,
                    'end_violation_flg' => $end_violation_flg,
                );
            }else{
                $violation_warning_array[] = array(
                    'start_violation_flg' => $start_violation_flg,
                    'end_violation_flg' => $end_violation_flg,
                );
            }
        }

        //t001_打刻データ取得
        $t001_web_punch_clock_info = $model_t001_web_punch_clock->getDataWithinTerm($employee_id, $target_start_serial, $target_end_serial);

        //t003_勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);
        //データ取得
        $model_m034_36agreement = new m034_36agreement();
        $model_m036_36agreement_max_time = new m036_36agreement_max_time();
        //36協定適用ID取得
        $thirtysix_agreement_apply_id = m007_employee::find($employee_id)->thirtysix_agreement_apply_id;
        //36協定ID
        $thirtysix_agreement_info = $model_m034_36agreement->getThirtysixAgreementData($thirtysix_agreement_apply_id, $target_start_serial);
        //36協定特別条項適用上限回数取得
        $thirtysix_agreement_special_provisions_max_count = $model_m034_36agreement->getThirtysixAgreementData($thirtysix_agreement_apply_id, $target_start_serial) == null ? 0 : $model_m034_36agreement->getThirtysixAgreementData($thirtysix_agreement_apply_id, $target_start_serial)->thirtysix_agreement_special_provisions_max_count;
        //36協定集計単位区分ID(月間)
        $month_thirtysix_agreement_aggregate_class_id = 1;
        //特別条項適用回数
        $thirtysix_agreement_special_provisions_apply_class = 0;
        if($thirtysix_agreement_apply_id == 2){
            if($t003_attendance_aggregate != null){
                $thirtysix_agreement_special_provisions_apply_class = $t003_attendance_aggregate->thirtysix_agreement_special_provisions_apply_class;
            }
        }
        //年間上限時間
        if($thirtysix_agreement_info != null){
            $max_time_year_thirtysix_disapply = $model_m036_36agreement_max_time->getThirtysixAgreementId($thirtysix_agreement_info->thirtysix_agreement_id,2)->max_time;
            $max_time_year_thirtysix_apply = $model_m036_36agreement_max_time->getThirtysixAgreementId($thirtysix_agreement_info->thirtysix_agreement_id,4)->max_time;
            $max_time_month = $model_m036_36agreement_max_time->getThirtysixAgreementId($thirtysix_agreement_info->thirtysix_agreement_id,$month_thirtysix_agreement_aggregate_class_id)->max_time;
        }else{
            $max_time_year_thirtysix_disapply = 0;
            $max_time_year_thirtysix_apply = 0;
            $max_time_month = 0;
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
                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($employee_id, $start_week_start_time_serial, $target_start_serial) <= 40 * 60){
                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($employee_id, $start_week_start_time_serial, $start_week_start_time_serial + 6) - 40 * 60;
                    if($actual_work_time_week <= 0){
                        continue;
                    }
                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($employee_id, $start_week_start_time_serial, $start_week_start_time_serial + 6);
                    if($holiday_work_time_week >= $actual_work_time_week){
                        $holiday_work_time += $actual_work_time_week;
                    }else{
                        $holiday_work_time += $holiday_work_time_week;
                    }
                }
            }else if($i == $week_no){
                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($employee_id, $end_week_start_time_serial, $target_end_serial) > 40 * 60){
                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($employee_id, $end_week_start_time_serial, $end_week_start_time_serial + 6) - 40 * 60;
                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($employee_id, $end_week_start_time_serial, $end_week_start_time_serial + 6);
                    if($holiday_work_time_week >= $actual_work_time_week){
                        $holiday_work_time += $actual_work_time_week;
                    }else{
                        $holiday_work_time += $holiday_work_time_week;
                    }
                }
            }else{
                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($employee_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) > 40 * 60){
                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($employee_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) - 40 * 60;
                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($employee_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6);
                    if($holiday_work_time_week >= $actual_work_time_week){
                        $holiday_work_time += $actual_work_time_week;
                    }else{
                        $holiday_work_time += $holiday_work_time_week;
                    }
                }
            }
        }
        $non_statutory_working_time = 0;
        if($t003_attendance_aggregate != null){
            $non_statutory_working_time = $t003_attendance_aggregate->non_statutory_working_time;
        }
        if($max_time_month != 0 && $thirtysix_agreement_apply_id == 2 && $holiday_work_time + $non_statutory_working_time > $max_time_month){
            $month_thirtysix_agreement_aggregate_class_id = 3;
            $max_time_month = $model_m036_36agreement_max_time->getThirtysixAgreementId($thirtysix_agreement_info->thirtysix_agreement_id,$month_thirtysix_agreement_aggregate_class_id)->max_time;
        }

        //t004_振替データ取得
        $model_t004_substitute_information = new t004_substitute_information();
        $t004_substitute_info = $model_t004_substitute_information->getSubstituteHolidayDateWithinTerm($employee_id, $target_start_serial, $target_end_serial);

        $t004_substitute_until_this_month_info = $model_t004_substitute_information->getSubstituteHolidayWithinTerm($employee_id,0,$target_end_serial);

        //控除
        $model_t007_over_time_achievement = new t007_over_time_achievement();
        $over_time_achievement_info = $model_t007_over_time_achievement->getOverTimeAchievementWithinTerm($employee_id, $target_start_serial, $target_end_serial);

        //欠勤
        $model_t008_unemployed_information = new t008_unemployed_information();
        $unemployed_information_info = $model_t008_unemployed_information->getUnemployedInformationWithinTerm($employee_id, $target_start_serial, $target_end_serial);


        $model_m007_employee = new m007_employee();
        $employee_info = $model_m007_employee->getEmployeeData($employee_id);

        $grant_paid_leave_type_id = $employee_info->grant_paid_leave_type_id;
        if($grant_paid_leave_type_id === 0 || $grant_paid_leave_type_id === null){
            //対象外にする
            $grant_paid_leave_type_id = 5;
        }
        $manegement_target_class = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->manegement_target_class;
        $grant_paid_leave_month = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->grant_paid_leave_month;
        $grant_paid_leave_day = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->grant_paid_leave_day;
        $grant_paid_leave_month_day_number = $grant_paid_leave_month * 100 + $grant_paid_leave_day;


        $today_month_day_number = $cf->serialToMonthDayNumber($target_start_serial);

        $paid_leave_date_start = 0;
        $paid_leave_date_end = 0;

        if($grant_paid_leave_month_day_number <= $today_month_day_number){
            $paid_leave_date_start = $cf->serialToYearNumber($target_start_serial) * 10000 + $grant_paid_leave_month_day_number;
            $paid_leave_date_end = ($cf->serialToYearNumber($target_start_serial) + 1) * 10000 + $grant_paid_leave_month_day_number;
        }else{
            $paid_leave_date_start = ($cf->serialToYearNumber($target_start_serial) - 1) * 10000 + $grant_paid_leave_month_day_number;
            $paid_leave_date_end = $cf->serialToYearNumber($target_start_serial) * 10000 + $grant_paid_leave_month_day_number;
        }

        $paid_leave_date_start_serial = $cf->numberToDateSerial($paid_leave_date_start);
        $paid_leave_date_end_serial = $cf->numberToDateSerial($paid_leave_date_end) - 1;

        //週所定日数
        $week_scheduled_working_days = $employee_info->week_scheduled_working_days;
        //勤続月数
        $grant_starting_year = $cf->serialToYearNumber($employee_info->grant_starting_date);
        $grant_starting_month = $cf->serialToMonthNumber($employee_info->grant_starting_date);
        $paid_leave_year_start = floor($paid_leave_date_start/10000);
        $paid_leave_month_start = floor($paid_leave_date_start/100) - $paid_leave_year_start * 100;

        $all_year_work_month = $paid_leave_month_start - $grant_starting_month + ($paid_leave_year_start - $grant_starting_year) * 12;
        
        $model_m033_grant_paid_leave_pattern = new m033_grant_paid_leave_pattern();
        //勤続月数は6か月未満の場合、6か月になります。
        if($all_year_work_month < 6){
            $all_year_work_month = 6;
        }
        $grant_paid_leave_pattern = $model_m033_grant_paid_leave_pattern->getGrantPaidLeavePattern($week_scheduled_working_days,$all_year_work_month);
        $obligatory_take_paid_leave_days = 0;
        //ー年間有給取得義務日数
        if($grant_paid_leave_pattern != null){
            $obligatory_take_paid_leave_days = $grant_paid_leave_pattern->obligatory_take_paid_leave_days;
        }

        $model_t010_acquired_holiday = new t010_acquired_holiday();
        $acquired_holiday_info = $model_t010_acquired_holiday->getUnemployedInformationWithinTerm($employee_id,$paid_leave_date_start_serial,$paid_leave_date_end_serial);

        //有休取得日数（年間）
        $acquired_paid_leave_days = 0;
        //保存休暇取得日数（年間）
        $accumulated_paid_leave_days = 0;

        foreach($acquired_holiday_info as $ah_info){
            $m031_info = m031_unemployed::find($ah_info->unemployed_id);
            if($m031_info->holiday_management_class == 1){
                $acquired_paid_leave_days += $ah_info->acquired_holiday_days + $ah_info->acquired_holiday_half_days / 2;
            }else if($m031_info->holiday_management_class == 2){
                $accumulated_paid_leave_days += $ah_info->acquired_holiday_days + $ah_info->acquired_holiday_half_days / 2;
            }
        }

        $acquired_holiday_until_this_month_info = $model_t010_acquired_holiday->getUnemployedInformationWithinTerm($employee_id,$paid_leave_date_start_serial,$target_end_serial);
        //有休取得日数（当月まで）
        $acquired_paid_leave_days_until_this_month = 0;
        foreach($acquired_holiday_until_this_month_info as $ahutm_info){
            $m031_info = m031_unemployed::find($ahutm_info->unemployed_id);
            if($m031_info->holiday_management_class == 1){
                $acquired_paid_leave_days_until_this_month += $ahutm_info->acquired_holiday_days + $ahutm_info->acquired_holiday_half_days / 2;
            }
        }

        $remaining_paid_leave_days = 0;
        $unused_accumulated_paid_leave_days = 0;
        if($t003_attendance_aggregate != null){
            //有休残日数
            $remaining_paid_leave_days = $t003_attendance_aggregate->remaining_paid_leave_days + $t003_attendance_aggregate->remaining_paid_leave_half_days / 2;
            //保存休暇残日数
            $unused_accumulated_paid_leave_days = $t003_attendance_aggregate->unused_accumulated_paid_leave_days + $t003_attendance_aggregate->unused_accumulated_paid_leave_half_days / 2;
        }

        $model_t009_holiday_management = new t009_holiday_management();
        //振替休日・休日振替出勤一覧
        $substitute_information_array = array();
        //休暇取得一覧
        $unemployed_information_array = array();
        //休暇付与一覧
        $holiday_management_array = array();
        
        $unused_substitute_information_days = 0;

        $substitute_information = $model_t004_substitute_information->getSubstituteHolidayWithinTerm($employee_id,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
        foreach($substitute_information as $s_info){
            $substitute_information_array[] = array(
                'substitute_information_id' => $s_info->substitute_information_id,
                'holiday_substitute_date' => $s_info->holiday_substitute_date,
                'substitute_holiday_date' => $s_info->substitute_holiday_date,
                'acquired_substitue_holiday_date' => $s_info->acquired_substitue_holiday_date,
                'substitute_reason' => $s_info->substitute_reason,
            );
            if($s_info->acquired_substitue_holiday_date == 0){
                $unused_substitute_information_days += 1;
            }
        }

        $unemployed_information = $model_t008_unemployed_information->getUnemployedInformationWithinTerm($employee_id,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
        foreach($unemployed_information as $u_info){
            $m031_info = m031_unemployed::find($u_info->unemployed_id);
            if($m031_info->unemployed_take_unit_class != 1 && $m031_info->unemployed_take_unit_class != 2){
                continue;
            }
            $unemployed_information_array[] = array(
                'unemployed_information_id' => $u_info->unemployed_information_id,
                'holiday_substitute_name' => $m031_info->unemployed_name,
                'target_date' => $u_info->target_date,
                'days' => $m031_info->unemployed_take_unit_class == 1 ? 1 : 0.5,
                'request_reason' => $u_info->request_reason,
            );
        }
        
        $holiday_management = $model_t009_holiday_management->getTargetHolidaysWithinTerm($employee_id,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
        foreach($holiday_management as $h_info){
            $m043_info = m043_holiday::find($h_info->holiday_id);
            $holiday_management_array[] = array(
                'holiday_management_id' => $h_info->holiday_management_id,
                'holiday_name' => $m043_info->holiday_name,
                'valid_date_start' => $h_info->valid_date_start,
                'grant_holiday_days' => $h_info->grant_holiday_days,
                'valid_date_end' => $h_info->valid_date_end,
            );
        }

        //返却用データ集約
        $input_attendance_info = [
            'attendance_information' => $t002_attendance_info,
            'attendance_aggregate' => $t003_attendance_aggregate,
            'substitute_information' => $t004_substitute_info,
            'substitute_until_this_month_info' => $t004_substitute_until_this_month_info,
            'over_time_achievement_info' => $over_time_achievement_info,
            'unemployed_information_info' => $unemployed_information_info,
            'close_date_id' => $close_date_id,
            'target_start_serial' => $target_start_serial,
            'target_end_serial' => $target_end_serial,
            'employee_list' => $employee_list,
            'violation_warning_array' => $violation_warning_array,
            'web_punch_clock_info' => $t001_web_punch_clock_info,
            'max_time_year_thirtysix_disapply' => $max_time_year_thirtysix_disapply,
            'max_time_year_thirtysix_apply' => $max_time_year_thirtysix_apply,
            'max_time_month' => $max_time_month,
            'thirtysix_agreement_special_provisions_apply_class' => $thirtysix_agreement_special_provisions_apply_class,
            'thirtysix_agreement_special_provisions_max_count' => $thirtysix_agreement_special_provisions_max_count,
            'holiday_work_time' => $holiday_work_time,
            'thirtysix_agreement_apply_id' => $thirtysix_agreement_apply_id,
            'paid_leave_date_start_serial' => $paid_leave_date_start_serial,
            'paid_leave_date_end_serial' => $paid_leave_date_end_serial,
            'obligatory_take_paid_leave_days' => $obligatory_take_paid_leave_days,
            'acquired_paid_leave_days_until_this_month' => $acquired_paid_leave_days_until_this_month,
            'acquired_paid_leave_days' => $acquired_paid_leave_days,
            'accumulated_paid_leave_days' => $accumulated_paid_leave_days,
            'remaining_paid_leave_days' => $remaining_paid_leave_days,
            'unused_accumulated_paid_leave_days' => $unused_accumulated_paid_leave_days,
            'unused_substitute_information_days' => $unused_substitute_information_days,
            'substitute_information_array' => $substitute_information_array,
            'unemployed_information' => $unemployed_information_array,
            'holiday_management' => $holiday_management_array,
            'work_holiday_short_name_array' => $work_holiday_short_name_array,
            'grant_paid_leave_type_id' => $grant_paid_leave_type_id,
            'manegement_target_class' => $manegement_target_class,
        ];

        return response()->json([
            'result' => true,
            'values' => $input_attendance_info,
        ]);
    }
}
