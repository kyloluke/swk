<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m005_dept;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m031_unemployed;
use App\Models\m033_grant_paid_leave_pattern;
use App\Models\m040_36agreement_apply;
use App\Models\m046_grant_paid_leave_type;
use App\Models\t005_set_approval_target;
use App\Models\t006_set_input_agent;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t010_acquired_holiday;
use App\Models\t011_holiday_worker_information;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;
use Illuminate\Support\Facades\DB;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class TargetPersonnelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 対象者一覧取得
     */
    public function getTargetList(Request $request)
    {
        $target_date = $request->input('target_date');
        if(!$target_date)
        {
            //入力エラー
            return response()->json([
                'result' => false,
                'values' => "parameter error",
            ]);
        }
        $setting_target_type = $request->input('setting_target_type');
        if(!($setting_target_type == 1 || $setting_target_type == 2))
        {
            //入力エラー
            return response()->json([
                'result' => false,
                'values' => "parameter error",
            ]);
        }
        
        //ログイン中の社員情報を取得
        $employee_id = $request->session()->get('employee')->employee_id;

        // モデルのインスタンス化 (代理入力者設定)
        $model_object = $setting_target_type == 1 ? new t005_set_approval_target() : new t006_set_input_agent();
        // 代理入力者ID、基準日から被代理入力者IDを取得
        $target_info = $model_object->getSortIDTargetID($employee_id, $target_date);

        // モデルのインスタンス化 (社員情報)
        $employee_func = new EmployeeInfoFunctions();
        
        $target_array = array();

        foreach($target_info as $info)
        {
            $employee_id = $setting_target_type == 1 ? $info->approved_person_id : $info->input_delegator_id;
            //社員情報取得
            $target_employee_data = $employee_func->getEmployeeInfo($employee_id, $target_date);

            // 社員コード
            $target_employee_code = $target_employee_data->employee_code != null ? $target_employee_data->employee_code : '';

            $target_array[] = array(
                'employee_id' => $employee_id,
                'employee_code' => $target_employee_code,
                'employee_name' => $target_employee_data->employee_name,
                'employee_dept' => $target_employee_data->dept->dept_name,
                'employee_post' => $target_employee_data->post->post_name,
                'employee_sort' => $setting_target_type == 1 ? $info->set_approval_target_id : $info->set_input_agent_id,
            );
        }

        // ソートキー
        $target_array_employee_code = array_column($target_array, 'employee_code');
        array_multisort($target_array_employee_code, SORT_ASC, $target_array);

        return response()->json([
            'result' => true,
            'values' => $target_array,
        ]);
    }
    /**
     * 勤務状態一覧取得
     */
    public function getApprovalTargetList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $employee_func = new EmployeeInfoFunctions();
        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');

        $selected_employee_id_list = $request->input('SelectedEmployeeIdList');

        $is_selected_single = $request->input('isSelectedSingle');

        $isCheckedThirtysixApply = $request->input('isCheckedThirtysixApply') == 'true';
        $isCheckedThirtysixUnapply = $request->input('isCheckedThirtysixUnapply') == 'true';

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        $close_date = m016_close_date::find($close_date_id)->close_date;

        //年月（requestから対象年月取得）
        $approval_target_term = $request->input('approvalTargetDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $approval_target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //締め日を取得
        $close_term = $cf->getCloseTerm($approval_target_term, $close_date);
        $approval_target_start_serial = $close_term['start_sereial'];
        $approval_target_end_serial = $close_term['end_sereial'];

        //先月
        $last_month = $cf->calcYearMonth($approval_target_term, -1);

        //承認対象者データ取得
        $model_t005_set_approval_target = new t005_set_approval_target();
        $set_approval_target_info = $model_t005_set_approval_target->getTargetID($employee_id, $approval_target_end_serial);

        //T002_勤怠情報取得
        $model_t002_attendance_information = new t002_attendance_information();
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();

        $t002_attendance_info = $model_t002_attendance_information->getAttendanceInformationByTerm($approval_target_start_serial, $approval_target_end_serial);
        $t003_attendance_aggregate_info = $model_t003_attendance_aggregate->getAttendanceAggregateByTerm($approval_target_term);
        $last_month_t003_info = $model_t003_attendance_aggregate->getAttendanceAggregateByTerm($last_month);

        //Deptツリー取得用にインスタンス作成
        $m005_dept = new m005_dept();
        //返却用配列
        $approval_target_array = array();

        if($is_selected_single == 1){

            foreach($set_approval_target_info as $info)
            {
                //取得された対象の内、close_dateが一致するもののみを対象
                $employee_data = $employee_func->getEmployeeInfo($info->approved_person_id, $approval_target_end_serial);

                $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                    continue;
                }
                if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }

                //未申請件数、未承認件数、勤怠警告
                $unapplied_cnt = 0;
                $unapproved_cnt = 0;
                $violation_warning = 0;

                if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                {
                    continue;
                }
                //情報すべて取得して配列にセット
                //DEPTをツリーとして返す
                $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));

                foreach($t002_attendance_info as $attendance_info)
                {
                    if($attendance_info->employee_id == $info->approved_person_id){
    
                        //出休IDが「通常勤務」かつ承認状態IDが「初期状態」を未申請件数とする
                        if(($attendance_info->work_holiday_id == WORK_HOLIDAY_NORMAL) && ($attendance_info->approval_state_id == APPROVAL_STATE_INITIAL)){
                            $unapplied_cnt++;
                        }
                        //承認状態IDが「申請中」を未承認件数とする
                        else if($attendance_info->approval_state_id == APPROVAL_STATE_REQUEST){
                            $unapproved_cnt++;
                        }
    
                        //違反or警告の日があるか確認
                        if(!(($violation_warning == VIOLATION_WARNING_VIOLATION) || ($violation_warning == VIOLATION_WARNING_WARNING))){
                            $violation_warning = $attendance_info->violation_warning_id;
                        }
                    }
                }
    
                //当月の締め状態
                $close_state_thismonth = 0;
                foreach($t003_attendance_aggregate_info as $aggregate_info)
                {
                    if($aggregate_info->employee_id == $info->approved_person_id){
                        $close_state_thismonth = $aggregate_info->close_state_id;
                    }
                }
    
                //先月の締め状態
                $close_state_lastmonth = 0;
                foreach($last_month_t003_info as $last_month_info)
                {
                    if($last_month_info->employee_id == $info->approved_person_id){
                        $close_state_lastmonth = $last_month_info->close_state_id;
                    }
                }

                $approval_target_array[] = array(
                    'employee_id' => $employee_data->employee_id,
                    'employee_code' => $employee_data->employee_code,
                    'employee_name' => $employee_data->employee_name,
                    'office_name' =>$employee_data->office->office_name,
                    'dept_tree' => $dept,
                    'post_name' => $employee_data->post->post_name,
                    'unapplied_cnt' => $unapplied_cnt,
                    'unapproved_cnt' => $unapproved_cnt,
                    'violation_warning' => $violation_warning,
                    'close_state_thismonth' => $close_state_thismonth,
                    'close_state_lastmonth' => $close_state_lastmonth,
                );
                continue;
            }

            // ソートキー
            $target_array_employee_code = array_column($approval_target_array, 'employee_code');
            array_multisort($target_array_employee_code, SORT_ASC, $approval_target_array);

            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);

        }elseif($is_selected_single == 2){

           if($selected_employee_id_list != null){

                foreach($selected_employee_id_list as $EmployeeId)
                {
                    //取得された対象の内、close_dateが一致するもののみを対象
                    $employee_data = $employee_func->getEmployeeInfo($EmployeeId, $approval_target_end_serial);

                    $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                    if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                        continue;
                    }
                    if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }

                    //未申請件数、未承認件数、勤怠警告
                    $unapplied_cnt = 0;
                    $unapproved_cnt = 0;
                    $violation_warning = 0;

                    if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                    {
                        continue;
                    }
                    //情報すべて取得して配列にセット
                    //DEPTをツリーとして返す
                    $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));
                    
                    foreach($t002_attendance_info as $attendance_info)
                    {
                        if($attendance_info->employee_id == $EmployeeId){
        
                            //出休IDが「通常勤務」かつ承認状態IDが「初期状態」を未申請件数とする
                            if(($attendance_info->work_holiday_id == WORK_HOLIDAY_NORMAL) && ($attendance_info->approval_state_id == APPROVAL_STATE_INITIAL)){
                                $unapplied_cnt++;
                            }
                            //承認状態IDが「申請中」を未承認件数とする
                            else if($attendance_info->approval_state_id == APPROVAL_STATE_REQUEST){
                                $unapproved_cnt++;
                            }
        
                            //違反or警告の日があるか確認
                            if(!(($violation_warning == VIOLATION_WARNING_VIOLATION) || ($violation_warning == VIOLATION_WARNING_WARNING))){
                                $violation_warning = $attendance_info->violation_warning_id;
                            }
                        }
                    }
        
                    //当月の締め状態
                    $close_state_thismonth = 0;
                    foreach($t003_attendance_aggregate_info as $aggregate_info)
                    {
                        if($aggregate_info->employee_id == $EmployeeId){
                            $close_state_thismonth = $aggregate_info->close_state_id;
                        }
                    }
        
                    //先月の締め状態
                    $close_state_lastmonth = 0;
                    foreach($last_month_t003_info as $last_month_info)
                    {
                        if($last_month_info->employee_id == $EmployeeId){
                            $close_state_lastmonth = $last_month_info->close_state_id;
                        }
                    }

                    $approval_target_array[] = array(
                        'employee_id' => $employee_data->employee_id,
                        'employee_code' => $employee_data->employee_code,
                        'employee_name' => $employee_data->employee_name,
                        'office_name' =>$employee_data->office->office_name,
                        'dept_tree' => $dept,
                        'post_name' => $employee_data->post->post_name,
                        'unapplied_cnt' => $unapplied_cnt,
                        'unapproved_cnt' => $unapproved_cnt,
                        'violation_warning' => $violation_warning,
                        'close_state_thismonth' => $close_state_thismonth,
                        'close_state_lastmonth' => $close_state_lastmonth,
                    );
                    continue;
                }
            }
            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);
        }else{

            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);

        }
    }
    

    /**
     * 36協定チェック一覧取得
     */
    public function getThirtySixCheckList(Request $request)
    {

        //共通関数
        $cf = new CommonFunctions();
        $employee_func = new EmployeeInfoFunctions();
        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');

        $selected_employee_id_list = $request->input('SelectedEmployeeIdList');

        $is_selected_single = $request->input('isSelectedSingle');

        $isCheckedThirtysixApply = $request->input('isCheckedThirtysixApply') == 'true';
        $isCheckedThirtysixUnapply = $request->input('isCheckedThirtysixUnapply') == 'true';

        $year_month_list = $request->input('yearMonthList');

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        $close_date = m016_close_date::find($close_date_id)->close_date;

        //年月（requestから対象年月取得）
        $approval_target_term = $request->input('approvalTargetDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $approval_target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //締め日を取得
        $close_term = $cf->getCloseTerm($approval_target_term, $close_date);
        $approval_target_start_serial = $close_term['start_sereial'];
        $approval_target_end_serial = $close_term['end_sereial'];

        //承認対象者データ取得
        $model_t005_set_approval_target = new t005_set_approval_target();
        $set_approval_target_info = $model_t005_set_approval_target->getTargetID($employee_id, $approval_target_end_serial);

        $model_t002_attendance_information = new t002_attendance_information();
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();

        //Deptツリー取得用にインスタンス作成
        $m005_dept = new m005_dept();
        //返却用配列
        $approval_target_array = array();

        if($is_selected_single == 1){

            foreach($set_approval_target_info as $info)
            {
                //取得された対象の内、close_dateが一致するもののみを対象
                $employee_data = $employee_func->getEmployeeInfo($info->approved_person_id, $approval_target_end_serial);

                $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                    continue;
                }
                if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }

                if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                {
                    continue;
                }
                //情報すべて取得して配列にセット
                //DEPTをツリーとして返す
                $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));

                $four_week_count = 0;
                $attendance_information = $model_t002_attendance_information->getAttendanceInformationWithinTerm($info->approved_person_id, $approval_target_start_serial, $approval_target_end_serial);
                foreach($attendance_information as $a_info){
                    if(($a_info->work_holiday_id == 2 || $a_info->work_holiday_id == 3) && ($a_info->work_achievement_id == 0 || $a_info->work_achievement_id == 9)){
                        $four_week_count += 1;
                    }
                }

                $over_time_year_array = array();
                $over_time_six_month_array = array();

                for($j = count($year_month_list) - 1 ; $j > -1; $j--){

                    $month_close_term = $cf->getCloseTerm($year_month_list[$j], $close_date);
                    $target_start_serial = $month_close_term['start_sereial'];
                    $target_end_serial = $month_close_term['end_sereial'];

                    $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($info->approved_person_id, $year_month_list[$j]);

                    //法定外休日出勤時間(残業時間加算)
                    $start_week_start_time_serial = $target_start_serial - $cf->serialToWeek($target_start_serial);
                    $end_week_start_time_serial = $target_end_serial - $cf->serialToWeek($target_end_serial);
                    $week_no = ($end_week_start_time_serial - $start_week_start_time_serial) / 7;
                    $holiday_work_time = 0;
                    $actual_work_time_week = 0;
                    $holiday_work_time_week = 0;
                    for($i = 0 ; $i <= $week_no ; $i++){
                        if($i == 0){
                            if($model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $start_week_start_time_serial, $target_start_serial) <= 40 * 60){
                                $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $start_week_start_time_serial, $start_week_start_time_serial + 6) - 40 * 60;
                                if($actual_work_time_week <= 0){
                                    continue;
                                }
                                $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($info->approved_person_id, $start_week_start_time_serial, $start_week_start_time_serial + 6);
                                if($holiday_work_time_week >= $actual_work_time_week){
                                    $holiday_work_time += $actual_work_time_week;
                                }else{
                                    $holiday_work_time += $holiday_work_time_week;
                                }
                            }
                        }else if($i == $week_no){
                            if($model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $end_week_start_time_serial, $target_end_serial) > 40 * 60){
                                $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $end_week_start_time_serial, $end_week_start_time_serial + 6) - 40 * 60;
                                $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($info->approved_person_id, $end_week_start_time_serial, $end_week_start_time_serial + 6);
                                if($holiday_work_time_week >= $actual_work_time_week){
                                    $holiday_work_time += $actual_work_time_week;
                                }else{
                                    $holiday_work_time += $holiday_work_time_week;
                                }
                            }
                        }else{
                            if($model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) > 40 * 60){
                                $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) - 40 * 60;
                                $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($info->approved_person_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6);
                                if($holiday_work_time_week >= $actual_work_time_week){
                                    $holiday_work_time += $actual_work_time_week;
                                }else{
                                    $holiday_work_time += $holiday_work_time_week;
                                }
                            }
                        }
                    }

                    if($t003_attendance_aggregate != null){
                        $holiday_work_time += $t003_attendance_aggregate->non_statutory_working_time;
                    }
                    
                    $over_time_year_array[] = array(
                        'month' => $year_month_list[$j],
                        'holiday_work_time' => $holiday_work_time,
                    );

                    if($year_month_list[$j] <= $approval_target_term){
                        if(count($over_time_six_month_array) >= 6){
                            continue;
                        }
                        $over_time_six_month_array[] = array(
                            'month' => $year_month_list[$j],
                            'holiday_work_time' => $holiday_work_time,
                        );
                    }

                }
                
                while(count($over_time_six_month_array) < 6){

                    $target_month = $cf->calcYearMonth($over_time_six_month_array[count($over_time_six_month_array) - 1]['month'],-1);

                    $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($info->approved_person_id, $target_month);

                    $month_close_term = $cf->getCloseTerm($target_month, $close_date);
                    $target_start_serial = $month_close_term['start_sereial'];
                    $target_end_serial = $month_close_term['end_sereial'];

                    //法定外休日出勤時間(残業時間加算)
                    $start_week_start_time_serial = $target_start_serial - $cf->serialToWeek($target_start_serial);
                    $end_week_start_time_serial = $target_end_serial - $cf->serialToWeek($target_end_serial);
                    $week_no = ($end_week_start_time_serial - $start_week_start_time_serial) / 7;
                    $holiday_work_time = 0;
                    $actual_work_time_week = 0;
                    $holiday_work_time_week = 0;
                    for($i = 0 ; $i <= $week_no ; $i++){
                        if($i == 0){
                            if($model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $start_week_start_time_serial, $target_start_serial) <= 40 * 60){
                                $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $start_week_start_time_serial, $start_week_start_time_serial + 6) - 40 * 60;
                                if($actual_work_time_week <= 0){
                                    continue;
                                }
                                $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($info->approved_person_id, $start_week_start_time_serial, $start_week_start_time_serial + 6);
                                if($holiday_work_time_week >= $actual_work_time_week){
                                    $holiday_work_time += $actual_work_time_week;
                                }else{
                                    $holiday_work_time += $holiday_work_time_week;
                                }
                            }
                        }else if($i == $week_no){
                            if($model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $end_week_start_time_serial, $target_end_serial) > 40 * 60){
                                $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id, $end_week_start_time_serial, $end_week_start_time_serial + 6) - 40 * 60;
                                $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($info->approved_person_id, $end_week_start_time_serial, $end_week_start_time_serial + 6);
                                if($holiday_work_time_week >= $actual_work_time_week){
                                    $holiday_work_time += $actual_work_time_week;
                                }else{
                                    $holiday_work_time += $holiday_work_time_week;
                                }
                            }
                        }else{
                            if($model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) > 40 * 60){
                                $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($info->approved_person_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) - 40 * 60;
                                $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($info->approved_person_id,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6);
                                if($holiday_work_time_week >= $actual_work_time_week){
                                    $holiday_work_time += $actual_work_time_week;
                                }else{
                                    $holiday_work_time += $holiday_work_time_week;
                                }
                            }
                        }
                    }

                    if($t003_attendance_aggregate != null){
                        $holiday_work_time += $t003_attendance_aggregate->non_statutory_working_time;
                    }

                    $over_time_six_month_array[] = array(
                        'month' => $target_month,
                        'holiday_work_time' => $holiday_work_time,
                    );

                }

                $approval_target_array[] = array(
                    'employee_id' => $employee_data->employee_id,
                    'employee_code' => $employee_data->employee_code,
                    'employee_name' => $employee_data->employee_name,
                    'office_name' =>$employee_data->office->office_name,
                    'dept_tree' => $dept,
                    'post_name' => $employee_data->post->post_name,
                    'over_time_year' => $over_time_year_array,
                    'over_time_six_month' => $over_time_six_month_array,
                    'four_week_count' => $four_week_count,
                );
                continue;
            }

            // ソートキー
            $target_array_employee_code = array_column($approval_target_array, 'employee_code');
            array_multisort($target_array_employee_code, SORT_ASC, $approval_target_array);

            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);

        }elseif($is_selected_single == 2){

            if($selected_employee_id_list != null){

                foreach($selected_employee_id_list as $EmployeeId)
                {
                    //取得された対象の内、close_dateが一致するもののみを対象
                    $employee_data = $employee_func->getEmployeeInfo($EmployeeId, $approval_target_end_serial);

                    $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                    if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                        continue;
                    }
                    if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }

                    if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                    {
                        continue;
                    }
                    //情報すべて取得して配列にセット
                    //DEPTをツリーとして返す
                    $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));
                    
                    $four_week_count = 0;
                    $attendance_information = $model_t002_attendance_information->getAttendanceInformationWithinTerm($EmployeeId, $approval_target_start_serial, $approval_target_end_serial);
                    foreach($attendance_information as $a_info){
                        if(($a_info->work_holiday_id == 2 || $a_info->work_holiday_id == 3) && ($a_info->work_achievement_id == 0 || $a_info->work_achievement_id == 9)){
                            $four_week_count += 1;
                        }
                    }
                    
                    $over_time_year_array = array();
                    $over_time_six_month_array = array();

                    for($j = count($year_month_list) - 1 ; $j > -1; $j--){

                        $month_close_term = $cf->getCloseTerm($year_month_list[$j], $close_date);
                        $target_start_serial = $close_term['start_sereial'];
                        $target_end_serial = $close_term['end_sereial'];

                        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($EmployeeId, $year_month_list[$j]);
    
                        //法定外休日出勤時間(残業時間加算)
                        $start_week_start_time_serial = $target_start_serial - $cf->serialToWeek($target_start_serial);
                        $end_week_start_time_serial = $target_end_serial - $cf->serialToWeek($target_end_serial);
                        $week_no = ($end_week_start_time_serial - $start_week_start_time_serial) / 7;
                        $holiday_work_time = 0;
                        $actual_work_time_week = 0;
                        $holiday_work_time_week = 0;
                        for($i = 0 ; $i <= $week_no ; $i++){
                            if($i == 0){
                                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $start_week_start_time_serial, $target_start_serial) <= 40 * 60){
                                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $start_week_start_time_serial, $start_week_start_time_serial + 6) - 40 * 60;
                                    if($actual_work_time_week <= 0){
                                        continue;
                                    }
                                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($EmployeeId, $start_week_start_time_serial, $start_week_start_time_serial + 6);
                                    if($holiday_work_time_week >= $actual_work_time_week){
                                        $holiday_work_time += $actual_work_time_week;
                                    }else{
                                        $holiday_work_time += $holiday_work_time_week;
                                    }
                                }
                            }else if($i == $week_no){
                                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $end_week_start_time_serial, $target_end_serial) > 40 * 60){
                                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $end_week_start_time_serial, $end_week_start_time_serial + 6) - 40 * 60;
                                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($EmployeeId, $end_week_start_time_serial, $end_week_start_time_serial + 6);
                                    if($holiday_work_time_week >= $actual_work_time_week){
                                        $holiday_work_time += $actual_work_time_week;
                                    }else{
                                        $holiday_work_time += $holiday_work_time_week;
                                    }
                                }
                            }else{
                                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) > 40 * 60){
                                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) - 40 * 60;
                                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($EmployeeId,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6);
                                    if($holiday_work_time_week >= $actual_work_time_week){
                                        $holiday_work_time += $actual_work_time_week;
                                    }else{
                                        $holiday_work_time += $holiday_work_time_week;
                                    }
                                }
                            }
                        }
    
                        if($t003_attendance_aggregate != null){
                            $holiday_work_time += $t003_attendance_aggregate->non_statutory_working_time;
                        }

                        $over_time_year_array[] = array(
                            'month' => $year_month_list[$j],
                            'holiday_work_time' => $holiday_work_time,
                        );
    
                        if($year_month_list[$j] <= $approval_target_term){
                            if(count($over_time_six_month_array) >= 6){
                                continue;
                            }
                            $over_time_six_month_array[] = array(
                                'month' => $year_month_list[$j],
                                'holiday_work_time' => $holiday_work_time,
                            );
                        }
    
                    }

                    while(count($over_time_six_month_array) < 6){
                        $target_month = $cf->calcYearMonth($over_time_six_month_array[count($over_time_six_month_array) - 1]['month'],-1);
    
                        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($EmployeeId, $target_month);

                        $month_close_term = $cf->getCloseTerm($target_month, $close_date);
                        $target_start_serial = $month_close_term['start_sereial'];
                        $target_end_serial = $month_close_term['end_sereial'];
    
                        //法定外休日出勤時間(残業時間加算)
                        $start_week_start_time_serial = $target_start_serial - $cf->serialToWeek($target_start_serial);
                        $end_week_start_time_serial = $target_end_serial - $cf->serialToWeek($target_end_serial);
                        $week_no = ($end_week_start_time_serial - $start_week_start_time_serial) / 7;
                        $holiday_work_time = 0;
                        $actual_work_time_week = 0;
                        $holiday_work_time_week = 0;
                        for($i = 0 ; $i <= $week_no ; $i++){
                            if($i == 0){
                                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $start_week_start_time_serial, $target_start_serial) <= 40 * 60){
                                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $start_week_start_time_serial, $start_week_start_time_serial + 6) - 40 * 60;
                                    if($actual_work_time_week <= 0){
                                        continue;
                                    }
                                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($EmployeeId, $start_week_start_time_serial, $start_week_start_time_serial + 6);
                                    if($holiday_work_time_week >= $actual_work_time_week){
                                        $holiday_work_time += $actual_work_time_week;
                                    }else{
                                        $holiday_work_time += $holiday_work_time_week;
                                    }
                                }
                            }else if($i == $week_no){
                                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $end_week_start_time_serial, $target_end_serial) > 40 * 60){
                                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId, $end_week_start_time_serial, $end_week_start_time_serial + 6) - 40 * 60;
                                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($EmployeeId, $end_week_start_time_serial, $end_week_start_time_serial + 6);
                                    if($holiday_work_time_week >= $actual_work_time_week){
                                        $holiday_work_time += $actual_work_time_week;
                                    }else{
                                        $holiday_work_time += $holiday_work_time_week;
                                    }
                                }
                            }else{
                                if($model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) > 40 * 60){
                                    $actual_work_time_week = $model_t002_attendance_information->getActualWorkTimeWithinTerm($EmployeeId,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6) - 40 * 60;
                                    $holiday_work_time_week = $model_t002_attendance_information->getHolidayWorkTimeWithinTerm($EmployeeId,  ($i * 7) + $start_week_start_time_serial,  ($i * 7) + $start_week_start_time_serial + 6);
                                    if($holiday_work_time_week >= $actual_work_time_week){
                                        $holiday_work_time += $actual_work_time_week;
                                    }else{
                                        $holiday_work_time += $holiday_work_time_week;
                                    }
                                }
                            }
                        }
                        
                        if($t003_attendance_aggregate != null){
                            $holiday_work_time += $t003_attendance_aggregate->non_statutory_working_time;
                        }

                        $over_time_six_month_array[] = array(
                            'month' => $target_month,
                            'holiday_work_time' => $holiday_work_time,
                        );
    
                    }

                    $approval_target_array[] = array(
                        'employee_id' => $employee_data->employee_id,
                        'employee_code' => $employee_data->employee_code,
                        'employee_name' => $employee_data->employee_name,
                        'office_name' =>$employee_data->office->office_name,
                        'dept_tree' => $dept,
                        'post_name' => $employee_data->post->post_name,
                        'over_time_year' => $over_time_year_array,
                        'over_time_six_month' => $over_time_six_month_array,
                        'four_week_count' => $four_week_count,
                    );
                    continue;
                }
            }
            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);
        }else{

            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);

        }
    }



    /**
     * 休日出勤者一覧取得
     */
    public function getHolidayWorkerInformationList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $employee_func = new EmployeeInfoFunctions();
        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');

        $selected_employee_id_list = $request->input('SelectedEmployeeIdList');

        $is_selected_single = $request->input('isSelectedSingle');

        $isCheckedThirtysixApply = $request->input('isCheckedThirtysixApply') == 'true';
        $isCheckedThirtysixUnapply = $request->input('isCheckedThirtysixUnapply') == 'true';

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        $close_date = m016_close_date::find($close_date_id)->close_date;

        //年月（requestから対象年月取得）
        $approval_target_term = $request->input('approvalTargetDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $approval_target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //締め日を取得
        $close_term = $cf->getCloseTerm($approval_target_term, $close_date);
        $approval_target_start_serial = $close_term['start_sereial'];
        $approval_target_end_serial = $close_term['end_sereial'];

        //承認対象者データ取得
        $model_t005_set_approval_target = new t005_set_approval_target();
        $set_approval_target_info = $model_t005_set_approval_target->getTargetID($employee_id, $approval_target_end_serial);

        //T002_勤怠情報取得
        $model_t011_holiday_worker_information = new t011_holiday_worker_information();
        $t011_holiday_worker_info = $model_t011_holiday_worker_information->getHolidayWorkerInformationWithinTerm($approval_target_start_serial, $approval_target_end_serial);


        //Deptツリー取得用にインスタンス作成
        $m005_dept = new m005_dept();
        //返却用配列
        $approval_target_array = array();

        if($is_selected_single == 1){

            foreach($set_approval_target_info as $info)
            {
                //取得された対象の内、close_dateが一致するもののみを対象
                $employee_data = $employee_func->getEmployeeInfo($info->approved_person_id, $approval_target_end_serial);

                $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                    continue;
                }
                if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }

                if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                {
                    continue;
                }
                //情報すべて取得して配列にセット
                //DEPTをツリーとして返す
                $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));

                foreach($t011_holiday_worker_info as $holiday_worker_info)
                {
                    if($holiday_worker_info->employee_id == $info->approved_person_id){
                        $approval_target_array[] = array(
                            'employee_id' => $employee_data->employee_id,
                            'employee_code' => $employee_data->employee_code,
                            'employee_name' => $employee_data->employee_name,
                            'office_name' =>$employee_data->office->office_name,
                            'dept_tree' => $dept,
                            'post_name' => $employee_data->post->post_name,
                            'holiday_work_date' => $holiday_worker_info->holiday_work_date,
                            'holiday_work_reason' => $holiday_worker_info->holiday_work_reason,
                        );
                    }
                }
                continue;
            }

            // ソートキー
            $target_array_employee_code = array_column($approval_target_array, 'employee_code');
            array_multisort($target_array_employee_code, SORT_ASC, $approval_target_array);

            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);

        }elseif($is_selected_single == 2){

           if($selected_employee_id_list != null){

                foreach($selected_employee_id_list as $EmployeeId)
                {
                    //取得された対象の内、close_dateが一致するもののみを対象
                    $employee_data = $employee_func->getEmployeeInfo($EmployeeId, $approval_target_end_serial);

                    $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                    if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                        continue;
                    }
                    if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }

                    if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                    {
                        continue;
                    }
                    //情報すべて取得して配列にセット
                    //DEPTをツリーとして返す
                    $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));
                    
                    foreach($t011_holiday_worker_info as $holiday_worker_info)
                    {
                        if($holiday_worker_info->employee_id == $EmployeeId){
                            $approval_target_array[] = array(
                                'employee_id' => $employee_data->employee_id,
                                'employee_code' => $employee_data->employee_code,
                                'employee_name' => $employee_data->employee_name,
                                'office_name' =>$employee_data->office->office_name,
                                'dept_tree' => $dept,
                                'post_name' => $employee_data->post->post_name,
                                'holiday_work_date' => $holiday_worker_info->holiday_work_date,
                                'holiday_work_reason' => $holiday_worker_info->holiday_work_reason,
                            );
                        }
                    }
                    continue;
                }
            }
            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);
        }else{

            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);

        }
    }

    /**
     * 有休・休日条件一覧取得
     */
    public function getAcquiredAndHolidayList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $employee_func = new EmployeeInfoFunctions();
        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');

        $selected_employee_id_list = $request->input('SelectedEmployeeIdList');

        $is_selected_single = $request->input('isSelectedSingle');

        $isCheckedThirtysixApply = $request->input('isCheckedThirtysixApply') == 'true';
        $isCheckedThirtysixUnapply = $request->input('isCheckedThirtysixUnapply') == 'true';

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        $close_date = m016_close_date::find($close_date_id)->close_date;

        //年月（requestから対象年月取得）
        $approval_target_term = $request->input('approvalTargetDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $approval_target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //締め日を取得
        $close_term = $cf->getCloseTerm($approval_target_term, $close_date);
        $approval_target_start_serial = $close_term['start_sereial'];
        $approval_target_end_serial = $close_term['end_sereial'];

        //承認対象者データ取得
        $model_t005_set_approval_target = new t005_set_approval_target();
        $set_approval_target_info = $model_t005_set_approval_target->getTargetID($employee_id, $approval_target_end_serial);

        //T002_勤怠情報取得
        $model_m007_employee = new m007_employee();

        //Deptツリー取得用にインスタンス作成
        $m005_dept = new m005_dept();
        //返却用配列
        $approval_target_array = array();

        if($is_selected_single == 1){

            foreach($set_approval_target_info as $info)
            {
                //取得された対象の内、close_dateが一致するもののみを対象
                $employee_data = $employee_func->getEmployeeInfo($info->approved_person_id, $approval_target_end_serial);

                $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                    continue;
                }
                if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }
                if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                    continue;
                }

                if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                {
                    continue;
                }
                //情報すべて取得して配列にセット
                //DEPTをツリーとして返す
                $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));

                $grant_paid_leave_type_id = $employee_data->grant_paid_leave_type_id;
                if($grant_paid_leave_type_id === 0 || $grant_paid_leave_type_id === null){
                    //対象外にする
                    $grant_paid_leave_type_id = 5;
                }
                $manegement_target_class = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->manegement_target_class;
                $grant_paid_leave_month = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->grant_paid_leave_month;
                $grant_paid_leave_day = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->grant_paid_leave_day;
                $grant_paid_leave_month_day_number = $grant_paid_leave_month * 100 + $grant_paid_leave_day;
        
        
                $today_month_day_number = $cf->serialToMonthDayNumber($approval_target_start_serial);
        
                $paid_leave_date_start = 0;
                $paid_leave_date_end = 0;
        
                if($grant_paid_leave_month_day_number <= $today_month_day_number){
                    $paid_leave_date_start = $cf->serialToYearNumber($approval_target_start_serial) * 10000 + $grant_paid_leave_month_day_number;
                    $paid_leave_date_end = ($cf->serialToYearNumber($approval_target_start_serial) + 1) * 10000 + $grant_paid_leave_month_day_number;
                }else{
                    $paid_leave_date_start = ($cf->serialToYearNumber($approval_target_start_serial) - 1) * 10000 + $grant_paid_leave_month_day_number;
                    $paid_leave_date_end = $cf->serialToYearNumber($approval_target_start_serial) * 10000 + $grant_paid_leave_month_day_number;
                }
        
                $paid_leave_date_start_serial = $cf->numberToDateSerial($paid_leave_date_start);
                $paid_leave_date_end_serial = $cf->numberToDateSerial($paid_leave_date_end) - 1;

                //週所定日数
                $week_scheduled_working_days = $employee_data->week_scheduled_working_days;
                //勤続月数
                $grant_starting_year = $cf->serialToYearNumber($employee_data->grant_starting_date);
                $grant_starting_month = $cf->serialToMonthNumber($employee_data->grant_starting_date);
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
                $acquired_holiday_info = $model_t010_acquired_holiday->getUnemployedInformationWithinTerm($info->approved_person_id,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
        
                //有休取得日数（年間）
                $acquired_paid_leave_days = 0;
                foreach($acquired_holiday_info as $ah_info){
                    $m031_info = m031_unemployed::find($ah_info->unemployed_id);
                    if($m031_info->holiday_management_class == 1){
                        $acquired_paid_leave_days += $ah_info->acquired_holiday_days + $ah_info->acquired_holiday_half_days / 2;
                    }
                }
                //振替休日日残
                $unused_substitute_information_days = 0;
                $model_t004_substitute_information = new t004_substitute_information();
                $substitute_information = $model_t004_substitute_information->getSubstituteHolidayWithinTerm($info->approved_person_id,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
                foreach($substitute_information as $s_info){
                    if($s_info->substitute_holiday_date === 0 || $s_info->substitute_holiday_date > $paid_leave_date_end_serial){
                        $unused_substitute_information_days += 1;
                    }
                }
                $t004_substitute_until_this_month_info = $model_t004_substitute_information->getSubstituteHolidayWithinTerm($info->approved_person_id,0,$approval_target_end_serial);
                $approval_target_array[] = array(
                    'employee_id' => $employee_data->employee_id,
                    'employee_code' => $employee_data->employee_code,
                    'employee_name' => $employee_data->employee_name,
                    'office_name' =>$employee_data->office->office_name,
                    'dept_tree' => $dept,
                    'post_name' => $employee_data->post->post_name,
                    'paid_leave_date_end_serial' => $paid_leave_date_end_serial,
                    'acquired_paid_leave_days' => $acquired_paid_leave_days,
                    'obligatory_take_paid_leave_days' => $obligatory_take_paid_leave_days,
                    'unused_substitute_information_days' => $unused_substitute_information_days,
                    'substitute_until_this_month_info' => $t004_substitute_until_this_month_info,
                    'target_end_serial' => $approval_target_end_serial,
                    'grant_paid_leave_type_id' => $grant_paid_leave_type_id,
                    'manegement_target_class' => $manegement_target_class,
                );
                continue;
            }

            // ソートキー
            $target_array_employee_code = array_column($approval_target_array, 'employee_code');
            array_multisort($target_array_employee_code, SORT_ASC, $approval_target_array);

            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);

        }elseif($is_selected_single == 2){

           if($selected_employee_id_list != null){

                foreach($selected_employee_id_list as $EmployeeId)
                {
                    //取得された対象の内、close_dateが一致するもののみを対象
                    $employee_data = $employee_func->getEmployeeInfo($EmployeeId, $approval_target_end_serial);

                    $thirtysix_agreement_apply_class = m040_36agreement_apply::find($employee_data->thirtysix_agreement_apply_id)->thirtysix_agreement_apply_class;
                
                    if($isCheckedThirtysixApply && $isCheckedThirtysixUnapply && $thirtysix_agreement_apply_class == 0){
                        continue;
                    }
                    if($isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 9)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && $isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 0 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }
                    if(!$isCheckedThirtysixApply && !$isCheckedThirtysixUnapply && ($thirtysix_agreement_apply_class == 9 || $thirtysix_agreement_apply_class == 1)){
                        continue;
                    }

                    if($employee_data == null || $employee_data->close_date_id != $close_date_id)
                    {
                        continue;
                    }
                    //情報すべて取得して配列にセット
                    //DEPTをツリーとして返す
                    $dept = implode(' ', $m005_dept->getNameTree($employee_data->dept_id));

                    $grant_paid_leave_type_id = $employee_data->grant_paid_leave_type_id;
                    if($grant_paid_leave_type_id === 0 || $grant_paid_leave_type_id === null){
                        //対象外にする
                        $grant_paid_leave_type_id = 5;
                    }
                    $manegement_target_class = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->manegement_target_class;
                    $grant_paid_leave_month = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->grant_paid_leave_month;
                    $grant_paid_leave_day = m046_grant_paid_leave_type::find($grant_paid_leave_type_id)->grant_paid_leave_day;
                    $grant_paid_leave_month_day_number = $grant_paid_leave_month * 100 + $grant_paid_leave_day;
            
            
                    $today_month_day_number = $cf->serialToMonthDayNumber($approval_target_start_serial);
            
                    $paid_leave_date_start = 0;
                    $paid_leave_date_end = 0;
            
                    if($grant_paid_leave_month_day_number <= $today_month_day_number){
                        $paid_leave_date_start = $cf->serialToYearNumber($approval_target_start_serial) * 10000 + $grant_paid_leave_month_day_number;
                        $paid_leave_date_end = ($cf->serialToYearNumber($approval_target_start_serial) + 1) * 10000 + $grant_paid_leave_month_day_number;
                    }else{
                        $paid_leave_date_start = ($cf->serialToYearNumber($approval_target_start_serial) - 1) * 10000 + $grant_paid_leave_month_day_number;
                        $paid_leave_date_end = $cf->serialToYearNumber($approval_target_start_serial) * 10000 + $grant_paid_leave_month_day_number;
                    }
            
                    $paid_leave_date_start_serial = $cf->numberToDateSerial($paid_leave_date_start);
                    $paid_leave_date_end_serial = $cf->numberToDateSerial($paid_leave_date_end) - 1;
                    
                    //週所定日数
                    $week_scheduled_working_days = $employee_data->week_scheduled_working_days;
                    //勤続月数
                    $grant_starting_year = $cf->serialToYearNumber($employee_data->grant_starting_date);
                    $grant_starting_month = $cf->serialToMonthNumber($employee_data->grant_starting_date);
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
                    $acquired_holiday_info = $model_t010_acquired_holiday->getUnemployedInformationWithinTerm($EmployeeId,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
            
                    //有休取得日数（年間）
                    $acquired_paid_leave_days = 0;
                    foreach($acquired_holiday_info as $ah_info){
                        $m031_info = m031_unemployed::find($ah_info->unemployed_id);
                        if($m031_info->holiday_management_class == 1){
                            $acquired_paid_leave_days += $ah_info->acquired_holiday_days + $ah_info->acquired_holiday_half_days / 2;
                        }
                    }
                    //振替休日残
                    $unused_substitute_information_days = 0;
                    $model_t004_substitute_information = new t004_substitute_information();
                    $substitute_information = $model_t004_substitute_information->getSubstituteHolidayWithinTerm($EmployeeId,$paid_leave_date_start_serial,$paid_leave_date_end_serial);
                    foreach($substitute_information as $s_info){
                        if($s_info->substitute_holiday_date === 0 || $s_info->substitute_holiday_date > $paid_leave_date_end_serial){
                            $unused_substitute_information_days += 1;
                        }
                    }
                    $t004_substitute_until_this_month_info = $model_t004_substitute_information->getSubstituteHolidayWithinTerm($EmployeeId,0,$approval_target_end_serial);
                    $approval_target_array[] = array(
                        'employee_id' => $employee_data->employee_id,
                        'employee_code' => $employee_data->employee_code,
                        'employee_name' => $employee_data->employee_name,
                        'office_name' =>$employee_data->office->office_name,
                        'dept_tree' => $dept,
                        'post_name' => $employee_data->post->post_name,
                        'paid_leave_date_end_serial' => $paid_leave_date_end_serial,
                        'acquired_paid_leave_days' => $acquired_paid_leave_days,
                        'obligatory_take_paid_leave_days' => $obligatory_take_paid_leave_days,
                        'unused_substitute_information_days' => $unused_substitute_information_days,
                        'substitute_until_this_month_info' => $t004_substitute_until_this_month_info,
                        'target_end_serial' => $approval_target_end_serial,
                        'grant_paid_leave_type_id' => $grant_paid_leave_type_id,
                        'manegement_target_class' => $manegement_target_class,
                    );
                    continue;
                }
            }
            return response()->json([
                'result' => true,
                'values' => $approval_target_array,
            ]);
        }else{

            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);

        }
    }
    
    /**
     * 勤務状態一覧取得(事業所,総務)
     */
    public function getOfficeTargetList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        //部署IDを取得
        $dept_id = $request->input('deptId');
        //事業所IDを取得
        $office_id = $request->input('officeId');
        //社員番号を取得
        $employeeNumberInput = $request->input('employeeNumberInput');
        //名前を取得
        $name_input = $request->input('nameInput');
        //基準日を取得
        $date_serial = $request->input('referenceDate');
        //返却用配列
        $approval_target_array = array();

        //Deptツリー取得用にインスタンス作成
        $m005_dept = new m005_dept();

        //社員情報取得
        //社員番号と名前の部分一致で検索
        $target_employee = m007_employee::where('employee_name', 'like', "%$name_input%")
                                        ->where('employee_code', 'like', "%$employeeNumberInput%")
                                        ->where('is_delete', 0)
                                        ->get();
        $employee_func = new EmployeeInfoFunctions();

        foreach($target_employee as $info)
        {
            $employee_data = $employee_func->getEmployeeInfo($info->employee_id, $date_serial);
            //締め区分が入力と一致する社員を対象、または、締め区分入力無しの場合は全てを対象とする
            if(($close_date_id == $info->close_date_id) || ($close_date_id == 0)){

                //情報すべて取得して配列にセット
                //DEPTをツリーとして返す
                $dept = implode('／', $m005_dept->getNameTree($employee_data->dept_id));

                //取得された対象の内、部署ID,事業所IDが一致するもののみを対象、または、検索で部署入力なしの場合はすべて対象
                if( (($dept_id) && ($employee_data->dept_id != $dept_id))
                || (($office_id) && ($employee_data->office_id != $office_id)))
                {
                    continue;
                }

                $approval_target_array[] = array(
                    'employee_id' => $info->employee_id,
                    'employee_code' => $info->employee_code,
                    'employee_name' => $info->employee_name,
                    'office_name' =>$employee_data->office->office_name,
                    'dept_tree' => $dept,
                    'post_name' => $employee_data->post->post_name,
                    'close_date_id' => $info->close_date_id,
                );
            }

            continue;
        }
        return response()->json([
            'result' => true,
            'values' => $approval_target_array,
        ]);
    }
    /**
     * 選択済み対象者一覧取得
     */
    public function getSelectedTargetList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $employee_func = new EmployeeInfoFunctions();
        //ログイン中の社員情報を取得
        //$employee_id = $request->session()->get('employee')->employee_id;
        //ログイン中の、あるいは選択された社員情報を取得
        $employee_id = $request->input('employeeId');

        //承認対象者か代理入力者かを取得
        $setting_target_type = $request->input('setting_target_type');

        //基準日を取得
        $date_serial = $request->input('referenceDate');

        //Deptツリー取得用にインスタンス作成
        $m005_dept = new m005_dept();

        if($setting_target_type == SETTING_TARGET_TYPE_APPROVER){
            //承認対象者情報取得
            $model_t005_set_approval_target = new t005_set_approval_target();
            $target_info = $model_t005_set_approval_target->getTargetID($employee_id, $date_serial);
        }
        else{
            //代理入力者情報取得
            $model_t006_set_input_agent = new t006_set_input_agent();
            $target_info = $model_t006_set_input_agent->getTargetID($employee_id, $date_serial);
        }
        //検証
        if($target_info->isEmpty()){
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }
        $target_array = [];
        foreach($target_info as $info)
        {
            //承認対象者or代理入力者検索
            if($setting_target_type == SETTING_TARGET_TYPE_APPROVER){
                $search_employee_id = $info->approved_person_id;
            }
            else{
                $search_employee_id = $info->input_delegator_id;
            }
            $employee_data = $employee_func->getEmployeeInfo($search_employee_id, $date_serial);
            //DEPTをツリーとして返す
            $dept = implode('／', $m005_dept->getNameTree($employee_data->dept_id));

            $target_array[] = array(
                'employee_id' => $employee_data->employee_id,
                'employee_code' => $employee_data->employee_code,
                'employee_name' => $employee_data->employee_name,
                'office_name' =>$employee_data->office->office_name,
                'dept_tree' => $dept,
                'post_name' => $employee_data->post->post_name,
            );
            continue;
        }

        // ソートキー
        $target_array_employee_code = array_column($target_array, 'employee_code');
        array_multisort($target_array_employee_code, SORT_ASC, $target_array);

        return response()->json([
            'result' => true,
            'values' => $target_array,
        ]);
    }

    /**
     * 選択済み対象者登録
     */
    public function updateSelectedTargetList(Request $request)
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

        //ログイン中の、あるいは選択された社員情報を取得
        $employee_id = $params['employeeId'];
        $model_m007_employee = new m007_employee();
        //ログインされた社員コード
        $employee_code = $request->session()->get('employee')->employee_code;

        //承認対象者か代理入力者かを取得
        $setting_target_type = $params['setting_target_type'];

        //基準日を取得
        $reference_date = $params['referenceDate'];

        //選択済み対象者を取得
        $selected_employee_list = $params['info'];

        //使用するDBインスタンス作成
        if($setting_target_type == SETTING_TARGET_TYPE_APPROVER){
            $model_t005_set_approval_target = new t005_set_approval_target();
        }
        else{
            $model_t006_set_input_agent = new t006_set_input_agent();
        }

        $target_id = $employee_id;  //ログイン中の社員が承認者・代理入力者

        //現在日時取得
        date_default_timezone_set('Asia/Tokyo');
        $date = date('Y-m-d H:i:s');

        //削除（終了日が指定した基準日以降の対象者の終了日を、基準日-1でセットする）
        $valid_date_end = $reference_date - 1; //終了日：指定した基準日の前日
        if($setting_target_type == SETTING_TARGET_TYPE_APPROVER){
            $model_t005_set_approval_target->updateApprover($target_id, $valid_date_end, $employee_code, $date);
        }
        else{
            $model_t006_set_input_agent->updateInputAgent($target_id, $valid_date_end, $employee_code, $date);
        }
        
        //追加
        $valid_date_start = $reference_date; //開始日：指定した基準日
        $valid_date_end = DATE_SERIAL_MAX; //終了日：MAX値
        foreach($selected_employee_list as $selected){
            if($setting_target_type == SETTING_TARGET_TYPE_APPROVER){
                $detail_no = $model_t005_set_approval_target->last_detail_no()->detail_no + 1;
                $model_t005_set_approval_target->setData($detail_no, $target_id, $selected['employee_id'], $valid_date_start, $valid_date_end, $employee_code, $date);
            }
            else{
                $detail_no = $model_t006_set_input_agent->last_detail_no()->detail_no + 1;
                $model_t006_set_input_agent->setData($detail_no, $target_id, $selected['employee_id'], $valid_date_start, $valid_date_end, $employee_code, $date);
            }
        }

        return response()->json([
            'result' => true,
        ]);
    }
}
