<?php

namespace App\Http\Controllers;

use App\Http\AppLibs\ClosingFunctions;
use Illuminate\Http\Request;
use App\Models\t001_web_punch_clock;
use App\Models\t003_attendance_aggregate;
use App\Models\t002_attendance_information;
use App\Models\t004_substitute_information;
use App\Models\t005_set_approval_target;
use App\Models\t006_set_input_agent;
use App\Models\t007_over_time_achievement;
use App\Models\t008_unemployed_information;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m022_calendar_setting;
use App\Models\m023_work_zone;
use App\Models\m024_work_zone_time;
use App\Models\m028_web_punch_clock_deviation_time;
use App\Models\m030_work_achievement;
use App\Models\m031_unemployed;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;
use App\Http\AppLibs\LogFunctions as Log;
use App\Models\t010_acquired_holiday;
use Illuminate\Support\Facades\Auth;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 乖離判定実施
     */
    public function updateViolationWarningId(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');

        //年月（requestから対象年月取得）
        $attendance_year_month = $request->input('attendanceYearMonth');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $attendance_year_month))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
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
        $close_term = $cf->getCloseTerm($attendance_year_month, $close_date);
        $attendance_start_serial = $close_term['start_sereial'];
        $attendance_end_serial = $close_term['end_sereial'];

        //データ取得(年月はシリアル概念なし)
        $model_t002_attendance_information = new t002_attendance_information();

        $attendance_information_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $attendance_start_serial,$attendance_end_serial);
 
        $model_m028_web_punch_clock_deviation_time = new m028_web_punch_clock_deviation_time();

        //出勤許容する時間取得
        $start_allow_time = $model_m028_web_punch_clock_deviation_time->getAllowTime($m007_employee->deviation_time_before_start_time_id);
        //退勤許容する時間取得
        $end_allow_time = $model_m028_web_punch_clock_deviation_time->getAllowTime($m007_employee->deviation_time_after_end_time_id);

        $model_m024_work_zone_time = new m024_work_zone_time();

        $model_t001_web_punch_clock = new t001_web_punch_clock();

        $violation_warn_cnt = 0;
        //違反警告ID(乖離)
        foreach($attendance_information_info as $info)
        {
            if($info->attendance_date >= $cf->getTodaySerial()){
                break;
            }
            //申請状態が初期状態の以外の場合は更新しない
            if($info->approval_state_id != 1)
            {
                continue;
            }
            //通常出勤日の判定
            if($info->work_holiday_id == 1){
                //想定時間帯を取得(勤務時間：１)
                $start_end_time = $model_m024_work_zone_time->getStartEndTime($info->work_zone_id,1);
                if($start_end_time == null)
                {
                    continue;
                }

                //手入力打刻乖離
                if($model_t001_web_punch_clock->getInputDataWithinData($employee_id, $info->attendance_date, 1) != null || $model_t001_web_punch_clock->getInputDataWithinData($employee_id, $info->attendance_date, 2) != null){
                    //乖離あり
                    $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $info->attendance_date,2);
                    $violation_warn_cnt++;
                    continue;
                }

                if($start_allow_time->allow_before_start_time >= $start_end_time->start_time-$info->web_punch_clock_time_start &&
                    $info->web_punch_clock_time_start-$start_end_time->start_time <= $start_allow_time->allow_after_end_time &&
                    $end_allow_time->allow_before_start_time >= $start_end_time->end_time-$info->web_punch_clock_time_end &&
                    $info->web_punch_clock_time_end-$start_end_time->end_time <= $end_allow_time->allow_after_end_time){
                    //乖離なし
                    $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $info->attendance_date,1);
                }else{
                    //乖離あり
                    $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $info->attendance_date,2);
                    $violation_warn_cnt++;
                }
            //休日の判定
            }else if($info->work_holiday_id == 2 || $info->work_holiday_id == 3){
                //実績情報を取得
                $model_m030_work_achievement = new m030_work_achievement();
                $m030_work_achievement_data = $model_m030_work_achievement->getWorkAchievementDisplayClassByID($info->work_achievement_id);
                //休日出勤していない場合
                if($m030_work_achievement_data != null && $m030_work_achievement_data->work_achievement_display_class != 3 && $m030_work_achievement_data->work_achievement_display_class != 4){
                    //手入力打刻乖離
                    if($model_t001_web_punch_clock->getInputDataWithinData($employee_id, $info->attendance_date, 1) != null || $model_t001_web_punch_clock->getInputDataWithinData($employee_id, $info->attendance_date, 2) != null){
                        //乖離あり
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $info->attendance_date,2);
                        $violation_warn_cnt++;
                        continue;
                    }
                    //打刻がある場合は乖離あり
                    if($info->web_punch_clock_time_start != null || $info->web_punch_clock_time_end != null){
                        //乖離あり
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $info->attendance_date,2);
                        $violation_warn_cnt++;
                    }else{
                        //乖離なし
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $info->attendance_date,1);
                    }
                }
            }
        }

        //取り消し関連操作後の乖離判定タイミングでも締め状態のチェックを行う
        if($violation_warn_cnt > 0){
            $closeFunc = new ClosingFunctions();
            $login_employee_id = $request->session()->get('employee')->employee_id;
            $closeFunc->autoCloseManager($login_employee_id, $m007_employee->employee_id,$attendance_year_month,2);
        }
 
        return response()->json([
            'result' => true,
            'values' => [
            ]
        ]);
    }

    /**
     * 「対象者選択」の一覧表
     *  Params 
     *   targetType 1 = 承認対象者　／　2 = 代理対象者
     *   targetDate 日付のシリアル値
     */
    public function getInputAgentList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $employee_func = new EmployeeInfoFunctions();

        //対象の社員情報を取得
        $employee_id = $request->session()->get('employee')->employee_id;

        //タイプを指定
        $target_type = $request->input('targetType');

        //年月日（requestから対象年月日取得）
        $target_date = intval($request->input('targetDate'));

        //締め区分を取得
        $close_date_id = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;

        //年月
        $target_term = $cf->getTargetTerm($target_date, $close_date);
        //先月
        $last_month = $cf->calcYearMonth($target_term, -1);

        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];
        
        //T002_勤怠情報、T003_勤怠集計情報取得
        $model_t002_attendance_information = new t002_attendance_information();
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $model_t007_over_time_achievement = new t007_over_time_achievement();
        $model_t008_unemployed_information = new t008_unemployed_information();
        $model_m022_calendar_setting = new m022_calendar_setting();

        if($target_type == 1)
        {
            //承認対象者
            $t005_set_approval_target = new t005_set_approval_target();
            $target_info = $t005_set_approval_target->getSortIDTargetID($employee_id, $target_date);
        }
        else
        {
            //代理対象者
            $t006_set_input_agent = new t006_set_input_agent();
            $target_info = $t006_set_input_agent->getSortIDTargetID($employee_id, $target_date);
        }
        $model_m007_employee = new m007_employee();
        $target_array = [];
        foreach($target_info as $info)
        {
            if($target_type == 1){
                $target_employee_data = $model_m007_employee->getEmployeeData($info->approved_person_id);
            }else{
                $target_employee_data = $model_m007_employee->getEmployeeData($info->input_delegator_id);
            }

            // 社員コード
            if($target_employee_data != null){
                $target_employee_code = $target_employee_data->employee_code;
            }else{
                $target_employee_code = "";
            }
            if($target_type == 1){
                $target_array[] = array(
                    'employee_id' => $info->approved_person_id,
                    'employee_code' => $target_employee_code,
                    'employee_sort' => $info->set_approval_target_id,
                );
            }else{
                $target_array[] = array(
                    'employee_id' => $info->input_delegator_id,
                    'employee_code' => $target_employee_code,
                    'employee_sort' => $info->set_input_agent_id,
                );
            }
        }

        // ソートキー
        $target_array_employee_code = array_column($target_array, 'employee_code');
        array_multisort($target_array_employee_code, SORT_ASC, $target_array);

        $t002_attendance_info = $model_t002_attendance_information->getAttendanceInformationByTerm($target_start_serial, $target_end_serial);
        $t003_attendance_aggregate_info = $model_t003_attendance_aggregate->getAttendanceAggregateByTerm($target_term);
        $last_month_t003_info = $model_t003_attendance_aggregate->getAttendanceAggregateByTerm($last_month);
        $t007_over_time_achievement_info = $model_t007_over_time_achievement->getOverTimeAchievementByTerm($target_start_serial, $target_end_serial);
        $t008_unemployed_information_info = $model_t008_unemployed_information->getAllUnemployedInformationWithinTerm($target_start_serial, $target_end_serial);

        $ret_array = [];
        foreach($target_array as $target)
        {
            //事業所、勤怠締め所属事業所、役職、勤務形態
            $employee = $employee_func->getEmployeeInfo($target['employee_id'], $target_date);

            $employee->close_date_id = m007_employee::find($target['employee_id'])->close_date_id;
            //t005 or t006のID昇順並べ替え用
            $employee->employee_sort = $target['employee_sort'];
            //未申請件数、未承認件数、勤怠警告
            $unapplied_cnt = 0;
            $unapproved_cnt = 0;
            $violation_warning = 0;
            //控除時間
            $deduction_time = 0;
            //深夜時間
            $midnight_time = 0;

            foreach($t002_attendance_info as $info)
            {
                if($info->employee_id == $target['employee_id']){

                    //出休IDが「通常勤務」かつ承認状態IDが「初期状態」を未申請件数とする
                    if(($info->work_holiday_id == WORK_HOLIDAY_NORMAL) && ($info->approval_state_id == APPROVAL_STATE_INITIAL)){
                        $unapplied_cnt++;
                    }
                    //承認状態IDが「申請中」を未承認件数とする
                    else if($info->approval_state_id == APPROVAL_STATE_REQUEST){
                        $unapproved_cnt++;
                    }

                    //違反or警告の日があるか確認
                    if(!(($violation_warning == VIOLATION_WARNING_VIOLATION) || ($violation_warning == VIOLATION_WARNING_WARNING))){
                        $violation_warning = $info->violation_warning_id;
                    }

                    $deduction_time += $info->deduction_time;
                    $midnight_time += $info->midnight_time;
                }
            }

            $employee->unapplied_cnt = $unapplied_cnt;
            $employee->unapproved_cnt = $unapproved_cnt;
            $employee->violation_warning = $violation_warning;
            $employee->deduction_time = $deduction_time;
            $employee->midnight_time = $midnight_time;

            //当月の締め状態
            $close_state_thismonth = 0;
            //時間外時間
            $overtime_working_time = 0;
            
            foreach($t003_attendance_aggregate_info as $aggregate_info)
            {
                if($aggregate_info->employee_id == $target['employee_id']){
                    $close_state_thismonth = $aggregate_info->close_state_id;
                    $overtime_working_time = $aggregate_info->statutory_working_time + $aggregate_info->non_statutory_working_time;
                }
                $employee->close_state_thismonth = $close_state_thismonth;
                $employee->overtime_working_time = $overtime_working_time;
            }

            //先月の締め状態
            $close_state_lastmonth = 0;
            foreach($last_month_t003_info as $last_month_info)
            {
                if($last_month_info->employee_id == $target['employee_id']){
                    $close_state_lastmonth = $last_month_info->close_state_id;
                }
                $employee->close_state_lastmonth = $close_state_lastmonth;
            }

            //法定外休日勤務時間
            $over_time_non_statutory_holiday_work_time = 0;
            //法定休日勤務時間
            $over_time_statutory_holiday_work_time = 0;
            foreach($t007_over_time_achievement_info as $ota_info)
            {
                if($ota_info->employee_id == $target['employee_id']){
                    $m022_calendar_setting_info = $model_m022_calendar_setting->getCalendarSettingByDate(1,$ota_info->target_date);
                    if($m022_calendar_setting_info != null){
                        if($m022_calendar_setting_info->work_holiday_id == 2){
                            $over_time_non_statutory_holiday_work_time += $ota_info->over_time_end - $ota_info->over_time_start - $ota_info->over_time_rest_time - $ota_info->over_time_midnight_rest_time; //法定外休日勤務時間
                        }else if($m022_calendar_setting_info->work_holiday_id == 3){
                            $over_time_statutory_holiday_work_time += $ota_info->over_time_end - $ota_info->over_time_start - $ota_info->over_time_rest_time - $ota_info->over_time_midnight_rest_time; //法定休日勤務時間
                        }
                    }
                }
            }
            $employee->over_time_non_statutory_holiday_work_time = $over_time_non_statutory_holiday_work_time;
            $employee->over_time_statutory_holiday_work_time = $over_time_statutory_holiday_work_time;

            //欠勤控除対象時間
            $absent_deduction_target_time = 0;
            //育児等控除時間
            $chlid_deduction_time = 0;
            foreach($t008_unemployed_information_info as $ui_info)
            {
                if($ui_info->employee_id == $target['employee_id']){
                    $m031_unemployed_info = m031_unemployed::find($ui_info->unemployed_id);
                    if($m031_unemployed_info->deduction_target_class == 1){
                        $absent_deduction_target_time += $ui_info->unemployed_time;
                    }else if($m031_unemployed_info->deduction_target_class == 2){
                        $chlid_deduction_time += $ui_info->unemployed_time;
                    }
                }
            }
            $employee->absent_deduction_target_time = $absent_deduction_target_time;
            $employee->chlid_deduction_time = $chlid_deduction_time;

            $ret_array[] = $employee;
        }

        return response()->json([
            'result' => true,
            'values' => [
                'employee_array' => $ret_array,
            ]
        ]);
    }
    /**
     * 一括承認
     */
    public function approveCheckedAttendanceDetails(Request $request)
    {
        //共通関数
        $closingFunc = new ClosingFunctions();
        //入力値検証
        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $info_array = $params['info_array'];

        if($info_array == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }

        //承認者社員ID
        $login_employee_id = $request->session()->get('employee')->employee_id;

        //対象社員情報
        $employee_id = $params['employee_id'];
        $employee = m007_employee::find($employee_id);
        //勤怠情報インスタンス作成
        $t002_attendance_information = new t002_attendance_information();

        //承認
        //承認状態へ変更
        Log::info(2, 2, "承認実施", $employee->employee_id);
        foreach($info_array as $info){
            $t002_attendance_information->approve($info['option'], $login_employee_id);
        }

        //締め処理で使用する値を取得
        $close_state_id = $params['close_state_id'];
        $target_term = $params['yearMonth'];

        //管理者締め自動化処理の呼び出し
        $closingFunc->autoCloseManager($login_employee_id, $employee_id, $target_term, $close_state_id);

        return response()->json([
            'result' => true,
            'values' => [
                'message' => '',
            ]
        ]);

    }

    /**
     * 出休変更一括登録
     */
    public function changeWorkHoliday(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //入力値検証
        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $info_attendance_information = $params['attendanceInformationInfoList'];
        $employee_id = $params['employee_id'];
        if($info_attendance_information == null || $employee_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }

        //DBインスタンス作成
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();

        $employee = m007_employee::find($employee_id);
        //勤務帯IDを取得
        $work_zone_id = $employee->work_zone_id;
        $work_zone_info = m023_work_zone::find($work_zone_id);
        $model_m024_work_zone_time = new m024_work_zone_time();
        $start_end_time = $model_m024_work_zone_time->getStartEndTime($work_zone_id, 1);
        
        $actual_work_days = 0;
        $actual_work_time_sum = 0;
        foreach($info_attendance_information as $info){
            if($info['approval'] == '')
            {
                if($info['work_achievement_id'] == 0)
                {
                    $t002_attendance_information->updateWorkHoliday($info, null, null);
                }
                else
                {
                    $t002_attendance_information->updateWorkHoliday($info, $work_zone_info, $start_end_time);
                }
            }
            //実働時間と実働日数へ集計
            if($info['actual_work_time'] != '')
            {
                $actual_work_time_sum += $cf->getTimeSerial($info['actual_work_time']);
                $actual_work_days++;
            }
        }
        //t003のyear_month特定
        $close_date = $employee->close_date->close_date;
        $year_month = $cf->getTargetTerm(t002_attendance_information::find(reset($info_attendance_information)['attendance_information_id'])->attendance_date, $close_date);

        //実働時間と実働日数のみ更新
        $t003_attendance_aggregate->updateActualWork($employee_id, $year_month, $actual_work_time_sum, $actual_work_days);

        return response()->json([
            'result' => true,
            'values' => [
                'message' => '',
                'actual_work_time_sum' => $actual_work_time_sum,
                'actual_work_days' => $actual_work_days,
            ]
        ]);
    }

    /**
     * 振替休日変更一括登録
     */
    public function changeSubstitute(Request $request)
    {
        $login_employee_id = Auth::id();
        //共通関数
        $cf = new CommonFunctions();

        //入力値検証
        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $info_attendance_information = $params['attendanceInformationInfoList'];
        $employee_id = $params['employee_id'];
        $year_month = $params['year_month'];
        if($info_attendance_information == null || $employee_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }

        //締め区分を取得
        $employee = m007_employee::find($employee_id);
        $close_date_id = $employee->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        //締め日を取得
        $close_term = $cf->getCloseTerm($year_month, $close_date);
        $attendance_start_serial = $close_term['start_sereial'];
        $attendance_end_serial = $close_term['end_sereial'];

        //DBインスタンス作成
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $t004_substitute_information = new t004_substitute_information();

        $substitute_information = $t004_substitute_information->getSubstituteHolidayWithoutAcquired($employee_id);
        //当月以外の締めなかった「振休」の数
        $attendance_information_count = $t002_attendance_information->getSubstituteHolidayWithoutMonthCount($employee_id,$attendance_start_serial,$attendance_end_serial);

        $model_m024_work_zone_time = new m024_work_zone_time();
        foreach($info_attendance_information as $info){
            if($info['work_achievement_id'] == 9){
                $attendance_information_count += 1;
            }
        }
        $substitute_information_count = 0;
        if($substitute_information != null){
            $substitute_information_count = count($substitute_information);
        }
        if($attendance_information_count > $substitute_information_count){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '登録する振替休日の数が未取得の振替休日より多いため登録できません',
                ]
            ]);
        }

        //勤務帯IDを取得
        $work_zone_id = $employee->work_zone_id;
        $work_zone_info = m023_work_zone::find($work_zone_id);
        $model_m024_work_zone_time = new m024_work_zone_time();
        $start_end_time = $model_m024_work_zone_time->getStartEndTime($work_zone_id, 1);
        
        foreach($info_attendance_information as $info){
            //通常と振休のみ処理する
            if ($info['work_achievement_id'] != 1 && $info['work_achievement_id'] != 9) {
                continue;
            }

            if($info['work_achievement_id'] == 1){
                //当月振替休日削除
                //実績「振休」からの変更のみ、以外の場合は何もしないこと
                $t002_attendance_information->deleteSubstituteHolidayWithinTerm($employee_id,$info['attendance_date'],$work_zone_info, $start_end_time);
                $t004_substitute_information->deleteSubstituteHolidayWithinTerm($employee_id,$info['attendance_date']);
            } elseif ($info['work_achievement_id'] == 9) {
                //勤務帯から始業時間、終業時間、実働時間を算出
                $work_zone = m023_work_zone::find($employee->work_zone_id);
                $actual_time = 0;

                if ($work_zone != null) {
                    $work_zone_times_work = $model_m024_work_zone_time->getStartEndTime($employee->work_zone_id, 1);
                    $work_zone_times_rests = $model_m024_work_zone_time->getStartEndList($employee->work_zone_id, 2);
                    if ($work_zone_times_work) {

                        $actual_time += ($work_zone_times_work->end_time - $work_zone_times_work->start_time);
                    }
                    foreach ($work_zone_times_rests as $work_zone_times_rest) {
                        $actual_time -= ($work_zone_times_rest->end_time - $work_zone_times_rest->start_time);
                    }
                }

                //カレンダー取得
                $m022_calendar_setting = new m022_calendar_setting();
                $calendar_setting = $m022_calendar_setting->getCalendarSettingByDate($employee->calendar_id, $info['attendance_date']);
                $attendance_date = $info['attendance_date'];
                $work_holiday_id = $calendar_setting->work_holiday_id;
                $work_zone_id = $work_holiday_id == 1 ? $employee->work_zone_id : 0;
                $work_time_start = $work_holiday_id == 1 ? $work_zone_times_work->start_time : 0;
                $work_time_end = $work_holiday_id == 1 ? $work_zone_times_work->end_time : 0;
                $actual_work_time = $work_holiday_id == 1 ? $actual_time : 0;

                //現在日時取得
                date_default_timezone_set('Asia/Tokyo');
                $updated_at = date('Y-m-d H:i:s');
                $updated_user = $login_employee_id;

                //申請や仮申請の状態を取り消す時、必ずデフォルト値に戻す必要のカラム
                $columnArr = [
                    'approval_state_id' => 1,
                    'unemployed_id' => 0,
                    'statutory_working_time' => 0,
                    'non_statutory_working_time' => 0,
                    'midnight_time' => 0,
                    'break_time' => 0,
                    'midnight_break_time' => 0,
                    'holiday_work_break_time' => 0,
                    'holiday_midnight_work_break_time' => 0,
                    'deduction_time' => 0,
                    'unemployed_time' => 0,
                    'holiday_work_time' => 0,
                    'holiday_midnight_work_time' => 0,
                    'absent_time' => 0,
                    'information' => "",
                    'remand_reason' => "",
                    'approval_request_date' => 0,
                    'input_employee_id' => 0,
                    'approval_employee_id' => 0,
                    'work_time_start' => $work_time_start,
                    'work_time_end' => $work_time_end,
                    'updated_user' => $updated_user,
                    'updated_at' => $updated_at
                ];
                t002_attendance_information::specificColumnUpdate($info['attendance_information_id'], $columnArr);

                //該当の時間外実績、不就業、休暇取得情報を削除する
                $t007_over_time_achievement = new t007_over_time_achievement();
                $t008_unemployed_information = new t008_unemployed_information();
                $t010_acquired_holiday = new t010_acquired_holiday();
                $t007_over_time_achievement->deleteData($employee_id, $attendance_date);
                $t008_unemployed_information->deleteData($employee_id, $attendance_date);
                $t010_acquired_holiday->deleteData($employee_id, $attendance_date);

                $substitute_reason = $t004_substitute_information->updateDeletedSubstituteHoliday($employee_id, $info['attendance_date']);
                $t002_attendance_information->insertSubstituteHoliday($employee_id, $info['attendance_date'], $substitute_reason); //実績「通常」からの変更
                t002_attendance_information::specificColumnUpdate($info['attendance_information_id'], ['work_achievement_id' => 9, 'work_holiday_id' => 2]);//実績0(不就業や残業あり場合)からの変更
            }
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => '',
            ]
        ]);
    }
}
