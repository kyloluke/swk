<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m006_post;
use App\Models\m007_employee;
use App\Models\m011_authority_pattern;
use App\Models\m013_employment_style;
use App\Models\m016_close_date;
use App\Models\m021_calendar;
use App\Models\m022_calendar_setting;
use App\Models\m023_work_zone;
use App\Models\m033_grant_paid_leave_pattern;
use App\Models\m040_36agreement_apply;
use App\Models\m043_holiday;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t005_set_approval_target;
use App\Models\t006_set_input_agent;
use App\Models\t007_over_time_achievement;
use App\Models\t008_unemployed_information;
use App\Models\t009_holiday_management;  
use App\Models\t010_acquired_holiday;
use App\Models\t011_holiday_worker_information;
use App\Models\t018_office_history;
use App\Models\t019_work_closing_belonging_office_history;
use App\Models\t020_dept_history;
use App\Models\t021_post_history;
use App\Models\t023_employment_style_history;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;

include(dirname(__FILE__).'/../AppLibs/Const.php');
class DownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 承認対象者一覧ダウンロード
     */
    public function getAllApprovedList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $date_serial = $request->input('inputDate');

        if(!$cf->checkDate($cf->serialToDate($date_serial))){
            return response()->json([
                'result' => false,
            ]);
        }

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        // モデルのインスタンス化 (承認者設定)
        $model_t005_approval_target = new t005_set_approval_target();
        // 承認者ID、基準日から被承認者IDを取得
        $approved_info = $model_t005_approval_target->getAllApproved($date_serial);

        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();
 
        $approvalList = array();

        foreach($approved_info as $info)
        {
            //社員情報取得
            $approver_employee_data = $model_m007_employee->getEmployeeData($info->approver_id);
            $approved_person_employee_data = $model_m007_employee->getEmployeeData($info->approved_person_id);
            // 社員コード
            if($approver_employee_data != null){
                $approver_employee_code = $approver_employee_data->employee_code;
            }else{
                $approver_employee_code = "";
            }
            if($approved_person_employee_data != null){
                $approved_person_employee_code = $approved_person_employee_data->employee_code;
            }else{
                $approved_person_employee_code = "";
            }

            //会社ID
            if($approver_employee_data->company_id == $company_id && $approved_person_employee_data->company_id == $company_id){
                $approvalList[] = array(
                    'approver' => $approver_employee_code,
                    'approved_person' => $approved_person_employee_code,
                );
            }
        }

        // 第1ソートキー
        $approver  = array_column($approvalList, 'approver');
        // 第2ソートキー
        $approved_person = array_column($approvalList, 'approved_person');
        array_multisort($approver, SORT_ASC, $approved_person, SORT_ASC, $approvalList);

        return response()->json([
            'result' => true,
            'values' => $approvalList,
        ]);
    }

    /**
     * 代理入力者一覧ダウンロード
     */
    public function getAllAgentList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $date_serial = $request->input('inputDate');

        if(!$cf->checkDate($cf->serialToDate($date_serial))){
            return response()->json([
                'result' => false,
            ]);
        }

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        // モデルのインスタンス化 (代理入力者設定)
        $model_t006_set_input_agent = new t006_set_input_agent();
        // 代理入力者ID、基準日から被代理入力者IDを取得
        $agent_info = $model_t006_set_input_agent->getAllAgent($date_serial);

        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();
 
        $agentList = array();

        foreach($agent_info as $info)
        {
            //社員情報取得
            $agent_employee_data = $model_m007_employee->getEmployeeData($info->input_agent_id);
            $delegator_employee_data = $model_m007_employee->getEmployeeData($info->input_delegator_id);
            // 社員コード
            if($agent_employee_data != null){
                $agent_employee_code = $agent_employee_data->employee_code;
            }else{
                $agent_employee_code = "";
            }
            if($delegator_employee_data != null){
                $delegator_employee_code = $delegator_employee_data->employee_code;
            }else{
                $delegator_employee_code = "";
            }
            if($agent_employee_data->company_id == $company_id && $delegator_employee_data->company_id == $company_id){
                $agentList[] = array(
                    'agent' => $agent_employee_code,
                    'delegator' => $delegator_employee_code,
                );
            }
        }

        // 第1ソートキー
        $agent  = array_column($agentList, 'agent');
        // 第2ソートキー
        $delegator = array_column($agentList, 'delegator');
        array_multisort($agent, SORT_ASC, $delegator, SORT_ASC, $agentList);

        return response()->json([
            'result' => true,
            'values' => $agentList,
        ]);
    }

    /**
     * 未設定承認対象者一覧ダウンロード
     */
    public function getUnsetApprovedList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $date_serial = $request->input('inputDate');

        if(!$cf->checkDate($cf->serialToDate($date_serial))){
            return response()->json([
                'result' => false,
            ]);
        }

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        // モデルのインスタンス化 (承認者設定)
        $model_t005_approval_target = new t005_set_approval_target();
        // 承認者ID、基準日から被承認者IDを取得
        $approved_info = $model_t005_approval_target->getAllApproved($date_serial);

        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();
        $all_employee_info = $model_m007_employee->getAllEmployeeBelongCompanyWithinTerm($company_id,$date_serial);

        $model_m004_office = new m004_office();
        $model_m005_dept = new m005_dept();
        $model_m006_post = new m006_post();
        $employee_func = new EmployeeInfoFunctions();
        $employeeList = array();
        $approvedPersonList = array();

        foreach($approved_info as $approvedInfo){
            $approvedPersonList[] = array(
                'approved_person_id' => $approvedInfo->approved_person_id,
            );
        }

        $approved_person_id_array = array_column($approvedPersonList, 'approved_person_id');
        foreach($all_employee_info as $employeeInfo)
        {
            if(!in_array($employeeInfo->employee_id, $approved_person_id_array)){
                $employee_data = $employee_func->getEmployeeInfo($employeeInfo->employee_id, $date_serial);
                //事業所名取得
                $office_name = $model_m004_office->getName($employee_data->office_id);
                //部署名取得
                $dept_name = $model_m005_dept->getDeptName($employee_data->dept_id);
                //役職名取得
                $post_name = $model_m006_post->getPostNameWithNashi($employee_data->post_id);
                $employeeList[] = array(
                    'employee_id' => $employeeInfo->employee_id,
                    'employee_code' => $employeeInfo->employee_code,
                    'employee_name' => $employeeInfo->employee_name,
                    'office_name' => $office_name->office_name,
                    'dept_name' => $dept_name->dept_name,
                    'post_name' => $post_name,
                );
            }
        }

        return response()->json([
            'result' => true,
            'values' => $employeeList,
        ]);
    }

    /**
     * 未設定代理入力者一覧ダウンロード
     */
    public function getUnsetAgentList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $date_serial = $request->input('inputDate');

        if(!$cf->checkDate($cf->serialToDate($date_serial))){
            return response()->json([
                'result' => false,
            ]);
        }

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        // モデルのインスタンス化 (承認者設定)
        $model_t006_set_input_agent = new t006_set_input_agent();
        // 承認者ID、基準日から被承認者IDを取得
        $agent_info = $model_t006_set_input_agent->getAllAgent($date_serial);

        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();
        $all_employee_info = $model_m007_employee->getAllEmployeeBelongCompanyWithinTerm($company_id,$date_serial);
 
        $model_m004_office = new m004_office();
        $model_m005_dept = new m005_dept();
        $model_m006_post = new m006_post();
        $employee_func = new EmployeeInfoFunctions();
        $employeeList = array();
        $inputDelegatorList = array();
        
        foreach($agent_info as $agentInfo){
            $inputDelegatorList[] = array(
                'input_delegator_id' => $agentInfo->input_delegator_id,
            );
        }

        $input_delegator_id_array = array_column($inputDelegatorList, 'input_delegator_id');
        foreach($all_employee_info as $employeeInfo)
        {
            if(!in_array($employeeInfo->employee_id, $input_delegator_id_array)){
                $employee_data = $employee_func->getEmployeeInfo($employeeInfo->employee_id, $date_serial);
                //事業所名取得
                $office_name = $model_m004_office->getName($employee_data->office_id);
                //部署名取得
                $dept_name = $model_m005_dept->getDeptName($employee_data->dept_id);
                //役職名取得
                $post_name = $model_m006_post->getPostNameWithNashi($employee_data->post_id);
                $employeeList[] = array(
                    'employee_id' => $employeeInfo->employee_id,
                    'employee_code' => $employeeInfo->employee_code,
                    'employee_name' => $employeeInfo->employee_name,
                    'office_name' => $office_name->office_name,
                    'dept_name' => $dept_name->dept_name,
                    'post_name' => $post_name,
                );
            }
        }

        return response()->json([
            'result' => true,
            'values' => $employeeList,
        ]);
    }

    /**
     * 社員情報ダウンロード
     */
    public function getAllEmployeeList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $date_serial = $request->input('inputDate');
        
        if(!$cf->checkDate($cf->serialToDate($date_serial))){
            return response()->json([
                'result' => false,
            ]);
        }

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        // モデルのインスタンス化 (代理入力者設定)
        $model_m007_employee = new m007_employee();
        // 代理入力者ID、基準日から被代理入力者IDを取得
        $employee_info = $model_m007_employee->getAllEmployeeBelongCompany($company_id);
        $employeeList = array();

        $model_m004_office = new m004_office();
        $model_m005_dept = new m005_dept();
        $model_m006_post = new m006_post();
        $model_m021_calendar = new m021_calendar();
        $model_m023_work_zone = new m023_work_zone();
        $model_m016_close_date = new m016_close_date();
        $model_m013_employment_style = new m013_employment_style();
        $model_m040_36agreement_apply = new m040_36agreement_apply();
        $employee_func = new EmployeeInfoFunctions();

        foreach($employee_info as $info)
        {
            $employee_data = $employee_func->getEmployeeInfo($info->employee_id, $date_serial);
            //事業所名取得
            $office_name = $model_m004_office->getName($employee_data->office_id);
            //部署名取得
            $dept_name = $model_m005_dept->getDeptName($employee_data->dept_id);
            //勤怠締め所属事業所コード取得
            $work_closing_belonging_office_code = $model_m004_office->getCode($employee_data->work_closing_belonging_office_id);
            //勤怠締め所属事業所名取得
            $work_closing_belonging_office_name = $model_m004_office->getName($employee_data->work_closing_belonging_office_id);
            //役職名取得
            $post_name = $model_m006_post->getPostNameWithNashi($employee_data->post_id);
            //カレンダ名取得
            $calendar_name = $model_m021_calendar->getName($info->calendar_id);
            //個人カレンダ名取得
            $personal_calendar_name = $model_m021_calendar->getName($info->personal_calendar_id);
            //勤務帯コード取得
            $work_zone_code = $model_m023_work_zone->getCodeByID($info->work_zone_id);
            //勤務帯名取得
            $work_zone_name = $model_m023_work_zone->getNameByID($info->work_zone_id);
            //雇用形態名取得
            $employment_style_name = $model_m013_employment_style->getName($employee_data->employment_style_id);

            //締日名取得
            $close_date_name = $model_m016_close_date->getName($info->close_date_id);
            //36協定適用名取得
            $thirtysix_agreement_apply_name = $model_m040_36agreement_apply->getName($info->thirtysix_agreement_apply_id);

            $employeeList[] = array(
                'employee_id' => $info->employee_id,
                'employee_code' => $info->employee_code,
                'office_code' => $employee_data->office->office_code,
                'office_name' => $office_name->office_name,
                'dept_code' => $employee_data->dept->dept_code,
                'dept_name' => $dept_name->dept_name,
                'work_closing_belonging_office_code' => $work_closing_belonging_office_code->office_code,
                'work_closing_belonging_office_name' => $work_closing_belonging_office_name->office_name,
                'post_code' => $employee_data->post->post_code,
                'post_name' => $post_name,
                'employee_name' => $info->employee_name,
                'employee_kana_name' => $info->employee_kana_name,
                'gender' => $info->gender,
                'joined_company_date' => $info->joined_company_date,
                'retirement_company_date' => $info->retirement_company_date,
                'calendar_id' => $info->calendar_id,
                'calendar_name' => $calendar_name,
                'personal_calendar_id' => $info->personal_calendar_id,
                'personal_calendar_name' => $personal_calendar_name,
                'work_zone_code' => $work_zone_code->work_zone_code,
                'work_zone_name' => $work_zone_name->work_zone_name,
                'week_scheduled_working_days' => $info->week_scheduled_working_days,
                'scheduled_working_hours' => $info->scheduled_working_hours,
                'overtime_base_time' => $info->overtime_base_time,
                'available_input_class' => $info->available_input_class,
                'thirtysix_agreement_apply_id' => $info->thirtysix_agreement_apply_id,
                'thirtysix_agreement_apply_name' => $thirtysix_agreement_apply_name->thirtysix_agreement_apply_name,
                'employment_style_id' => $employee_data->employment_style_id,
                'employment_style_name' => $employment_style_name->employment_style_name,
                'close_date_id' => $info->close_date_id,
                'close_date_name' => $close_date_name->close_date_name,
                'grant_paid_leave_pattern_id' => $info->grant_paid_leave_pattern_id,
                'authority_pattern_id' => $info->authority_pattern_id,
                'first_paid_leave_date' => $info->first_paid_leave_date,
                'stamping_target_class' => $info->stamping_target_class,
                'email_address' => $info->email_address,
                'grant_starting_date' => $info->grant_starting_date,
                'work_management_target_class' => $info->work_management_target_class,
                'is_delete' => 0,
            );
        }

        return response()->json([
            'result' => true,
            'values' => $employeeList,
        ]);
    }

    /**
     * 給与連携データダウンロード
     */
    public function getSalaryAlignmentList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        
        //締め区分を取得
        $close_date_id = $request->input('closeDateId');
        //年月（requestから対象年月取得）
        $target_term = $request->input('inputDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        // モデルのインスタンス化
        $model_m016_close_date = new m016_close_date();
        $model_m022_calendar_setting = new m022_calendar_setting();
        $model_t002_attendance_information = new t002_attendance_information();
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $model_t004_substitute_information = new t004_substitute_information();
        $model_t007_over_time_achievement = new t007_over_time_achievement();

        //締め日を取得
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];
        $total1_start = $close_term['total1_start_sereial'];
        $total1_end = $close_term['total1_end_sereial'];
        $total2_start = $close_term['total2_start_sereial'];
        $total2_end = $close_term['total2_end_sereial'];

        $total1_start_week_id = $cf->serialToWeek($total1_start); //曜日（0 (日) から 6 (土)）
        $total1_end_week_id = $cf->serialToWeek($total1_end); //曜日（0 (日) から 6 (土)）
        $total2_start_week_id = $cf->serialToWeek($total2_start); //曜日（0 (日) から 6 (土)）
        $total2_end_week_id = $cf->serialToWeek($total2_end); //曜日（0 (日) から 6 (土)）

        //会社所属の全社員情報を取得
        $employee_info = m007_employee::where('company_id', $company_id)
                                        ->where('close_date_id', $close_date_id)
                                        ->where('work_management_target_class',1)
                                        ->where('is_delete', 0)
                                        ->where('retirement_company_date','>=', $target_end_serial)
                                        ->get();
        $employee_func = new EmployeeInfoFunctions();
        $employeeList = array();

        //カレンダー取得　今はカレンダーひとつ
        $calendar_id = 1;
        $calendar_info = $model_m022_calendar_setting->getCalendarSettingWithinTerm($calendar_id, $target_start_serial, $target_end_serial);
        //勤怠情報を取得
        $attendance_info = $model_t002_attendance_information->getAttendanceInformationByTerm($target_start_serial, $target_end_serial);
        //勤怠集計情報を取得
        $attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateByTerm($target_term);
        //振替情報を取得
        $substitute_info = $model_t004_substitute_information->getData();
        //時間外実績情報を取得
        $over_time_info = $model_t007_over_time_achievement->getOverTimeAchievementByTerm($target_start_serial, $target_end_serial);
        //不就業情報を取得
        $unemployed_info = t008_unemployed_information::whereBetween('target_date', [$target_start_serial, $target_end_serial])->where('is_delete', 0)->get();
        //休日出勤情報を取得
        $holiday_worker_info = t011_holiday_worker_information::whereBetween('holiday_work_date', [$target_start_serial, $target_end_serial])->where('is_delete', 0)->get();

        foreach($employee_info as $employee) //会社所属の全社員ループ
        {
            $absence_days = 0;
            $absent_time = 0;
            $over_time1 = 0;
            $over_time2 = 0;
            $legal_over_time1 = 0;
            $legal_over_time2 = 0;
            $transfer_over_time1 = 0;
            $transfer_over_time2 = 0;
            $transfer_legal_over_time1 = 0;
            $transfer_legal_over_time2 = 0;
            $hourly_workingtime_thismonth = 0;
            $hourly_workingtime_lastmonth = 0;
            $hourly_working_days = 0;
            $paid_days = 0;
            $accumulated_days = 0;
            $midnight_time = 0;
            $deduction_days = 0;
            $deduction_time = 0;
            $remain_paid_days = 0;
            $remain_accumulated_days = 0;
            $excess60hours_time1 = 0;
            $excess60hours_time2 = 0;
            $morning_duty = 0;

            $employee_id = $employee->employee_id; //社員ID
            $employee_data = $employee_func->getEmployeeInfo($employee_id, $target_start_serial);
            $is_hourly = $employee_data->employment_style->hourly_wage_target == 1;  //時給者
            $is_management_free       = $employee_data->employment_style->manegement_free_time == 0;  //管理職・裁量労働以外
            $is_management_management = $employee_data->employment_style->manegement_free_time == 1;  //管理職

            $scheduled_working_hours = $employee->scheduled_working_hours; //所定労働時間

            //振替情報(t004)ループ
            $transfer_over_time = 0;
            $is_work_sunday = false;
            $is_work_saturday = false;
            $work_transfer_legal_over_time = 0;
            foreach($substitute_info as $substitute)
            {

                if($employee_id == $substitute->employee_id){ //t004とemployee_id一致

                    if(!$is_management_management){  //管理職以外

                        $substitute_week_id = $cf->serialToWeek($substitute->holiday_substitute_date); //曜日（0 (日) から 6 (土)）

                        //振替出勤日の週の勤務情報を取得して所定が40H超えるか確認する（0 (日) から 6 (土)）
                        $substitute_attendance_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $substitute->holiday_substitute_date - $substitute_week_id, $substitute->holiday_substitute_date + (6 - $substitute_week_id));

                        $week_count = 0;
                        foreach($substitute_attendance_info as $substitute_attendance)
                        {
                            if($week_count == 0){ //日曜日
                                //週の始まりでクリア
                                $transfer_over_time = 0;
                                $is_work_sunday = false;
                                $is_work_saturday = false;
                                $work_transfer_legal_over_time = 0;
                            }
                            if($substitute_attendance->work_achievement_id == 7){ //休日勤務(振替休日申請あり)
                                if($week_count == 0){ //日曜日
                                    $is_work_sunday = true; //法定休日（日曜日）に出勤したフラグ
                                }
                                else{
                                    $is_work_saturday = true; //法定外休日（土曜日）に出勤したフラグ
                                }
                            }

                            if(1 <= $substitute_attendance->work_achievement_id && $substitute_attendance->work_achievement_id <= 7){ //出勤は所定時間分加算
                                $transfer_over_time += $scheduled_working_hours;
                            }
                            //金曜か土曜でしか週40H超えない
                            if($week_count == 5){
                                if($is_work_sunday){
                                    if($transfer_over_time > WEEKLY_LEGAL_WORK_MINUTES){ //法定休日に振替ありで出勤した、かつ、日曜～金曜までの間に所定労働が40Hを超えた場合は、超過分を振替法定休出時間へ加算

                                        if(($total1_start - $total1_start_week_id <= $substitute->substitute_holiday_date) && ($substitute->substitute_holiday_date <= $total1_end - $total1_end_week_id - 1)){ //振休が集計１期間（日～土の１週間を前にスライドして考慮）
                                            $transfer_legal_over_time1 += $transfer_over_time - WEEKLY_LEGAL_WORK_MINUTES; //振替法定休出時間
                                            $work_transfer_legal_over_time = $transfer_legal_over_time1;
                                        }
                                        else if(($total2_start - $total2_start_week_id <= $substitute->substitute_holiday_date) && ($substitute->substitute_holiday_date <= $total2_end - $total2_end_week_id - 1)){
                                            $transfer_legal_over_time2 += $transfer_over_time - WEEKLY_LEGAL_WORK_MINUTES; //振替法定休出時間
                                            $work_transfer_legal_over_time = $transfer_legal_over_time2;
                                        }
                                    }
                                }
                            }
                            else if($week_count == 6){ //土曜日
                                if($is_work_saturday){
                                    if($transfer_over_time > WEEKLY_LEGAL_WORK_MINUTES){ //法定外休日に振替ありで出勤した、かつ、日曜～金曜までの間に所定労働が40Hを超えた場合は、超過分を振替残業休出時間へ加算（日曜も振替ありで出勤の場合、振替法定休出時間分を減算）
                                        if(($total1_start - $total1_start_week_id <= $substitute->substitute_holiday_date) && ($substitute->substitute_holiday_date <= $total1_end - $total1_end_week_id - 1)){ //振休が集計１期間（日～土の１週間を前にスライドして考慮）
                                            $transfer_over_time1 += $transfer_over_time - WEEKLY_LEGAL_WORK_MINUTES - $work_transfer_legal_over_time; //振替残業休出時間
                                        }
                                        else if(($total2_start - $total2_start_week_id <= $substitute->substitute_holiday_date) && ($substitute->substitute_holiday_date <= $total2_end - $total2_end_week_id - 1)){
                                            $transfer_over_time1 += $transfer_over_time - WEEKLY_LEGAL_WORK_MINUTES - $work_transfer_legal_over_time; //振替残業休出時間
                                        }
                                    }
                                }
                            }
                            $week_count++;
                        }
                    }
                }
            }

            //勤怠情報(t002)ループ
            foreach($attendance_info as $attendance)
            {

                if($employee_id == $attendance->employee_id){ //t002とemployee_id一致

                    if($is_management_free){  //管理職・裁量労働以外
                        //集計１
                        if($total1_start <= $attendance->attendance_date && $attendance->attendance_date <= $total1_end){
                            if($is_management_free){  //管理職・裁量労働以外
                                $over_time1 += $attendance->statutory_working_time;           //残業休出時間 (法定内時間外)
                                $excess60hours_time1 += $attendance->statutory_working_time;  //60時間超過時間
                                $over_time1 += $attendance->non_statutory_working_time;       //残業休出時間 (法定外時間外)
                                $excess60hours_time1 += $attendance->non_statutory_working_time; //60時間超過時間
                            }
                        }
                        //集計２
                        else if($total2_start <= $attendance->attendance_date && $attendance->attendance_date <= $total2_end){
                            if($is_management_free){  //管理職・裁量労働以外
                                $over_time2 += $attendance->statutory_working_time;           //残業休出時間 (法定内時間外)
                                $excess60hours_time2 += $attendance->statutory_working_time;  //60時間超過時間
                                $over_time2 += $attendance->non_statutory_working_time;       //残業休出時間 (法定外時間外)
                                $excess60hours_time2 += $attendance->non_statutory_working_time; //60時間超過時間
                            }
                        }
                    }

                    if($is_hourly){ //時給者のみ

                        if((1 <= $attendance->work_achievement_id && $attendance->work_achievement_id <= 3) || $attendance->work_achievement_id == 7){ //出勤（休日出勤除く：残業休出時間に加算のため）の場合

                            //集計１
                            if($total1_start <= $attendance->attendance_date && $attendance->attendance_date <= $total1_end){
                                $hourly_workingtime_lastmonth += $attendance->actual_work_time - $attendance->statutory_working_time - $attendance->non_statutory_working_time;  //時給者前月出勤時間（残業除く：残業休出時間に加算のため）
                            }
                            //集計２
                            else if($total2_start <= $attendance->attendance_date && $attendance->attendance_date <= $total2_end){
                                $hourly_workingtime_thismonth += $attendance->actual_work_time - $attendance->statutory_working_time - $attendance->non_statutory_working_time;  //時給者出勤時間（残業除く：残業休出時間に加算のため）
                            }
                            $hourly_working_days++; //時給者出勤日数
                        }
                    }

                    if($total1_start <= $attendance->attendance_date){
                        //欠勤時間、欠勤日数
                        $absent_time += $attendance->absent_time;

                        if(!$is_management_management){  //管理職以外
                            //深夜残業時間
                            $midnight_time += $attendance->midnight_time;
                        }
                    }
                }
            }

            //勤怠集計情報(t003)ループ
            foreach($attendance_aggregate as $aggregate)
            {
                if($employee_id == $aggregate->employee_id){ //t003とemployee_id一致

                    //有休取得日数
                    $paid_days = $aggregate->acquired_paid_leave_days + ($aggregate->acquired_paid_leave_half_days / 2);
                    //保存休暇取得日数
                    $accumulated_days = $aggregate->accumulated_paid_leave_days + ($aggregate->accumulated_paid_leave_half_days / 2);
                    //有休残日数
                    $remain_paid_days = $aggregate->remaining_paid_leave_days + ($aggregate->remaining_paid_leave_half_days / 2);
                    //保存休暇残日数
                    $remain_accumulated_days = $aggregate->unused_accumulated_paid_leave_days + ($aggregate->unused_accumulated_paid_leave_half_days / 2);
                }
            }

            //時間外実績情報(t007)ループ
            foreach($over_time_info as $over_time)
            {
                if($is_management_management){  //管理職
                    //管理職は対象外　なにもしない
                    break;
                }
                else{ //管理職以外
                    if($employee_id == $over_time->employee_id){ //t007とemployee_id一致

                        //時間外開始～終了　―　休憩時間　―　深夜休憩時間　―　控除時間
                        $overtime = $over_time->over_time_end - $over_time->over_time_start - $over_time->over_time_rest_time - $over_time->over_time_midnight_rest_time - $over_time->deduction_time;

                        foreach($calendar_info as $calendar)
                        {
                            if($calendar->calendar_date == $over_time->target_date){

                                //集計１
                                if($total1_start <= $over_time->target_date && $over_time->target_date <= $total1_end){
                                    if($calendar->work_holiday_id == 2){ //所定休日
                                        $over_time1 += $overtime;
                                        $excess60hours_time1 += $overtime;
                                    }
                                    if($calendar->work_holiday_id == 3){ //法定休日
                                        $legal_over_time1 += $overtime;
                                        $excess60hours_time1 += $overtime;
                                    }
                                }
                                //集計２
                                if($total2_start <= $over_time->target_date && $over_time->target_date <= $total2_end){
                                    if($calendar->work_holiday_id == 2){ //所定休日
                                        $over_time2 += $overtime;
                                        $excess60hours_time2 += $overtime;
                                    }
                                    if($calendar->work_holiday_id == 3){ //法定休日
                                        $legal_over_time2 += $overtime;
                                        $excess60hours_time2 += $overtime;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //不就業情報(t008)ループ
            foreach($unemployed_info as $unemployed)
            {
                $is_deduction_childcare = $unemployed->unemployed->deduction_target_class == 2; //育児等控除
                $is_paid_leave = $unemployed->unemployed->paid_leave_target_class == 1; //有給

                if($employee_id == $unemployed->employee_id){ //t002とemployee_id一致
                    if($is_deduction_childcare){ //控除対象区分が育児等控除時間対象の不就業時間を集計
                        $deduction_time += $unemployed->unemployed_time;
                    }
                    if($is_hourly){ //時給者のみ
                        if($is_paid_leave){ //有給の場合
                            //集計１
                            if($total1_start <= $unemployed->target_date && $unemployed->target_date <= $total1_end){
                                $hourly_workingtime_lastmonth += $unemployed->unemployed_time;  //時給者前月出勤時間
                            }
                            //集計２
                            else if($total2_start <= $unemployed->target_date && $unemployed->target_date <= $total2_end){
                                $hourly_workingtime_thismonth += $unemployed->unemployed_time;  //時給者出勤時間
                            }
                            //時給者出勤日数
                            $hourly_working_days++;
                        }
                    }
                }
            }

            //休日実績時間情報(t011)ループ
            foreach($holiday_worker_info as $holiday_worker)
            {
                $is_holiday_work = $holiday_worker->work_achievement->work_achievement_id == 4; //休日勤務(振替休日申請なし) 振替休日に出勤した場合も含む
                $is_holiday_trip = $holiday_worker->work_achievement->work_achievement_id == 5; //休日出張(振替休日申請なし)

                if($is_management_management){  //管理職
                    //管理職は対象外　なにもしない
                }
                else{  //管理職以外
                    if($employee_id == $holiday_worker->employee_id){ //t011とemployee_id一致
                        foreach($calendar_info as $calendar)
                        {
                            if($calendar->calendar_date == $holiday_worker->holiday_work_date){
                                if($calendar->work_holiday_id == 1 || $calendar->work_holiday_id == 2){ //通常勤務、か、所定休日
                                    //集計１
                                    if($total1_start <= $holiday_worker->holiday_work_date && $holiday_worker->holiday_work_date <= $total1_end){
                                        if( ($is_holiday_work)    //休日勤務(振替休日申請なし) 振替休日に出勤した場合も含む
                                         || ($is_holiday_trip) ){ //休日出張(振替休日申請なし)
                                            $over_time1 += $holiday_worker->achievement_time;           //残業休出時間
                                            $excess60hours_time1 += $holiday_worker->achievement_time;  //60時間超過時間
                                        }
                                    }
                                    //集計２
                                    else if($total2_start <= $holiday_worker->holiday_work_date && $holiday_worker->holiday_work_date <= $total2_end){
                                        if( ($is_holiday_work)    //休日勤務(振替休日申請なし) 振替休日に出勤した場合も含む
                                         || ($is_holiday_trip) ){ //休日出張(振替休日申請なし)
                                            $over_time2 += $holiday_worker->achievement_time;           //残業休出時間
                                            $excess60hours_time2 += $holiday_worker->achievement_time;  //60時間超過時間
                                        }
                                    }
                                }
                                else if($calendar->work_holiday_id == 3){ //法定休日
                                    //集計１
                                    if($total1_start <= $holiday_worker->holiday_work_date && $holiday_worker->holiday_work_date <= $total1_end){

                                        if( ($is_holiday_work)    //休日勤務(振替休日申請なし) 振替休日に出勤した場合も含む
                                        || ($is_holiday_trip) ){ //休日出張(振替休日申請なし)
                                            $legal_over_time1 += $holiday_worker->achievement_time;     //法定休出時間
                                            $excess60hours_time1 += $holiday_worker->achievement_time;  //60時間超過時間
                                        }
                                    }
                                    //集計２
                                    else if($total2_start <= $holiday_worker->holiday_work_date && $holiday_worker->holiday_work_date <= $total2_end){

                                        if( ($is_holiday_work)    //休日勤務(振替休日申請なし) 振替休日に出勤した場合も含む
                                        || ($is_holiday_trip) ){ //休日出張(振替休日申請なし)
                                            $legal_over_time2 += $holiday_worker->achievement_time;     //法定休出時間
                                            $excess60hours_time2 += $holiday_worker->achievement_time;  //60時間超過時間
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //60時間超過時間２に引き継ぎ
            $excess60hours_time = $excess60hours_time1; 
            $excess60hours_time1 -= 3600;
            if($excess60hours_time1 < 0){
                $excess60hours_time1 = 0;
            }
            $excess60hours_time2 += $excess60hours_time; //集計１残業時間 + 集計２残業時間
            $excess60hours_time2 -= $excess60hours_time1; //集計１残業時間の60時間超過分を減算
            $excess60hours_time2 -= 3600;
            if($excess60hours_time2 < 0){
                $excess60hours_time2 = 0;
            }

            //育児等控除日数
            $deduction_days = floor($deduction_time / $employee->scheduled_working_hours);
            //育児等控除時間
            $deduction_time = $deduction_time % $employee->scheduled_working_hours;
            //欠勤日数
            $absence_days = floor($absent_time / $employee->scheduled_working_hours);
            //欠勤時間
            $absent_time = $absent_time % $employee->scheduled_working_hours;

            $employeeList[] = array(
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->employee_name,
                'absence_days' => $absence_days,                                                        //欠勤日数
                'absent_time' => floor($absent_time / 60 * 100) / 100,                                  //欠勤時間
                'over_time1' => ceil($over_time1 / 60 * 100) / 100,                                     //残業休出時間１
                'over_time2' => ceil($over_time2 / 60 * 100) / 100,                                     //残業休出時間２
                'legal_over_time1' => ceil($legal_over_time1 / 60 * 100) / 100,                         //法定休出時間１
                'legal_over_time2' => ceil($legal_over_time2 / 60 * 100) / 100,                         //法定休出時間２
                'transfer_over_time1' => ceil($transfer_over_time1 / 60 * 100) / 100,                   //振替残業休出時間１
                'transfer_over_time2' => ceil($transfer_over_time2 / 60 * 100) / 100,                   //振替残業休出時間２
                'transfer_legal_over_time1' => ceil($transfer_legal_over_time1 / 60 * 100) / 100,       //振替法定休出時間１
                'transfer_legal_over_time2' => ceil($transfer_legal_over_time2 / 60 * 100) / 100,       //振替法定休出時間２
                'hourly_workingtime_thismonth' => ceil($hourly_workingtime_thismonth / 60 * 100) / 100, //時給者出勤時間
                'hourly_workingtime_lastmonth' => ceil($hourly_workingtime_lastmonth / 60 * 100) / 100, //時給者前月出勤時間
                'hourly_working_days' => $hourly_working_days,                                          //時給者出勤日数
                'paid_days' => $paid_days,                                                              //有休取得日数
                'accumulated_days' => $accumulated_days,                                                //保存休暇取得日数
                'midnight_time' => ceil($midnight_time / 60 * 100) / 100,                               //深夜残業時間
                'deduction_days' => $deduction_days,                                                    //育児等控除日数
                'deduction_time' => floor($deduction_time / 60 * 100) / 100,                            //育児等控除時間
                'remain_paid_days' => $remain_paid_days,                                                //有休残日数
                'remain_accumulated_days' => $remain_accumulated_days,                                  //保存休暇残日数
                'excess60hours_time1' => ceil($excess60hours_time1 / 60 * 100) / 100,                   //60時間超過時間１
                'excess60hours_time2' => ceil($excess60hours_time2 / 60 * 100) / 100,                   //60時間超過時間２
                'morning_duty' => $morning_duty,                                                        //早朝当番回数
            );
        }

        return response()->json([
            'result' => true,
            'values' => $employeeList,
        ]);
    }

    /**
     * 承認対象と者代理入力者をアップロードする前、有効の社員数をチェックします
     */
    public function checkApproverAgent(Request $request)
    {
        $data = $request->input('data');
        $fileName = $request->input('fileName');
        $inputDate = $request->input('inputDate');
        $model_m007_employee = new m007_employee();
        $NewCount = 0;
        $UpdateCount = 0;
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        foreach($data as $info)
        {
            //社員ID取得
            $approver_agent_id = $model_m007_employee->getIdByCode(ltrim($info['approver_agent_code'], '0'),$company_id);
            $approved_delegator_id = $model_m007_employee->getIdByCode(ltrim($info['approved_delegator_code'], '0'),$company_id);
            if($approver_agent_id->first()  && $approved_delegator_id->first()){
                $NewCount += 1; 
            }
        }
        if($fileName == 'inputApprover'){
            $model_t005_approval_target = new t005_set_approval_target();
            $UpdateCount = $model_t005_approval_target->countApprovedUndeleted($inputDate);
        }else if($fileName == 'inputAgent'){
            $model_t006_set_input_agent = new t006_set_input_agent();
            $UpdateCount = $model_t006_set_input_agent->countAgentUndeleted($inputDate);
        }

        return response()->json([
            'result' => true,
            'NewCount' => $NewCount,
            'UpdateCount' => $UpdateCount,
        ]);
    }

    /**
     * 社員情報をアップロードする前、有効の社員数をチェックします
     */
    public function checkEmployee(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $data = $request->input('data');

        $model_m007_employee = new m007_employee();
        $model_m004_office = new m004_office();
        $model_m005_dept = new m005_dept();
        $model_m006_post = new m006_post();
        $model_m021_calendar = new m021_calendar();
        $model_m023_work_zone = new m023_work_zone();
        $model_m016_close_date = new m016_close_date();
        $model_m013_employment_style = new m013_employment_style();
        $model_m040_36agreement_apply = new m040_36agreement_apply();
        $model_m033_grant_paid_leave_pattern = new m033_grant_paid_leave_pattern();
        $model_m011_authority_pattern = new m011_authority_pattern();

        $NewCount = 0;
        $UpdateCount = 0;
        $errorMessage = '';
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        $today_serial = $cf->getTodaySerial();

        foreach($data as $info)
        {
            if($info['employee_id'] == ''){
                $NewCount += 1;
            }else{
                $UpdateCount += 1;
            }
            if($model_m007_employee->getSimpleEmployeeDataByID($info['employee_id']) == null){
                $errorMessage = '行目に無効な社員IDが指定されています';
                break;
            }
            if($model_m004_office->checkCode($info['office_code'],$company_id,$today_serial) == null){
                $errorMessage = '行目の事業所コード値にエラーがありました';
                break;
            }
            if($model_m005_dept->checkCode($info['dept_code'],$company_id,$today_serial) == null){
                $errorMessage = '行目の部署コード値にエラーがありました';
                break;
            }
            if($model_m004_office->checkCode($info['work_closing_belonging_office_code'],$company_id,$today_serial) == null){
                $errorMessage = '行目の勤怠締め所属事業所コード値にエラーがありました';
                break;
            }
            if($model_m006_post->checkCode($info['post_code'],$company_id) == null && $info['post_code'] != 0 && $info['post_code'] != null){
                $errorMessage = '行目の役職コード値にエラーがありました';
                break;
            }
            if($model_m013_employment_style->checkId($info['employment_style_id']) == null){
                $errorMessage = '行目の雇用形態ID値にエラーがありました';
                break;
            }
            if($model_m016_close_date->checkId($info['close_date_id']) == null){
                $errorMessage = '行目の締日ID値にエラーがありました';
                break;
            }
            if($model_m021_calendar->checkId($info['calendar_id']) == null){
                $errorMessage = '行目のカレンダID値にエラーがありました';
                break;
            }
            if($model_m021_calendar->checkId($info['personal_calendar_id']) == null){
                $errorMessage = '行目の個人カレンダID値にエラーがありました';
                break;
            }
            if($model_m023_work_zone->checkCode($info['work_zone_code'], $company_id) == null){
                $errorMessage = '行目の勤務帯コード値にエラーがありました';
                break;
            }
            if($model_m040_36agreement_apply->checkId($info['thirtysix_agreement_apply_id']) == null){
                $errorMessage = '行目の36協定適用ID値にエラーがありました';
                break;
            }
            if($model_m033_grant_paid_leave_pattern->checkId($info['grant_paid_leave_pattern_id']) == null){
                $errorMessage = '行目の有給付与日数パターンID値にエラーがありました';
                break;
            }
            if($model_m011_authority_pattern->checkId($info['authority_pattern_id']) == null){
                $errorMessage = '行目の権限パターンID値にエラーがありました';
                break;
            }
        }

        if($errorMessage != ''){
            $count = $NewCount + $UpdateCount;

            return response()->json([
                'result' => false,
                'errorMessage' => $errorMessage,
                'count' => $count,
            ]);
        }

        return response()->json([
            'result' => true,
            'NewCount' => $NewCount,
            'UpdateCount' => $UpdateCount,
        ]);
    }

    /**
     * 有給休暇をアップロードする前、有効のデータ数をチェックします
     */
    public function checkGrantPaidLeave(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $data = $request->input('data');
        $model_t009_holiday_management = new t009_holiday_management();
        $model_m007_employee = new m007_employee();
        $model_m033_grant_paid_leave_pattern = new m033_grant_paid_leave_pattern();
        $model_m043_holiday = new m043_holiday();
        
        $NewCount = 0;
        $UpdateCount = 0;
        $errorMessage = '';
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        $today_serial = $cf->getTodaySerial();

        for($i = 6; $i < count($data); $i++)
        {
            $NewCount += 1;
            if($model_m007_employee->getEmployeeAll($data[$i]['employee_code'],$company_id) == null){
                $errorMessage = '行目に無効な社員コードが指定されています';
                break;
            }
            if($model_m043_holiday->getHolidayNameById($data[$i]['holiday_id']) == null){
                $errorMessage = '行目に無効な休暇IDが指定されています';
                break;
            }
            //日付の書式のチェック
            if(!$cf->checkDate($data[$i]['valid_date_start'])){
                $errorMessage = '行目に無効な付与日が入力されています';
                break;
            }
            if(!$cf->checkDate($data[$i]['valid_date_end'])){
                $errorMessage = '行目に無効な期限日が入力されています';
                break;
            }
            if($cf->dateToSerial($data[$i]['valid_date_start']) > $cf->dateToSerial($data[$i]['valid_date_end'])){
                $errorMessage = '行目で期限日が付与日以前に入力されています';
                break;
            }
            //付与日数は正の整数
            if(!is_numeric($data[$i]['grant_holiday_days'])){
                $errorMessage = '行目に無効な付与日数が入力されています';
                break;
            }
            if(intval($data[$i]['grant_holiday_days']) < 1){
                $errorMessage = '行目にマイナス付与日数が入力されています';
                break;
            }
        }

        if($errorMessage != ''){
            $count = $NewCount + $UpdateCount;

            return response()->json([
                'result' => false,
                'errorMessage' => $errorMessage,
                'count' => $count,
            ]);
        }
        return response()->json([
            'result' => true,
            'NewCount' => $NewCount,
            'UpdateCount' => $UpdateCount,
        ]);
    }

    /**
     * 承認対象者一覧アップロード
     */
    public function uploadAllApprovedList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $data = $request->input('data');
        $employeeID = $request->input('employeeID');
        $inputDate = $request->input('inputDate');
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        $valid_date_start = $inputDate;          //追加ユーザの開始日：指定した基準日
        $valid_date_end_add = DATE_SERIAL_MAX;   //追加ユーザの終了日：MAX値
        $valid_date_end_update = $inputDate - 1; //削除ユーザの終了日：指定した基準日の前日

        //現在日時取得
        date_default_timezone_set('Asia/Tokyo');
        $date = date('Y-m-d H:i:s');

        // モデルのインスタンス化 (承認者設定)
        $model_t005_approval_target = new t005_set_approval_target();
        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();
        $employeeCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID);

        $model_t005_approval_target->updateApproverAlluser($valid_date_end_update, $employeeCode->employee_code, $date);
        foreach($data as $info)
        {
            //社員ID取得
            $approver_id = $model_m007_employee->getIdByCode(ltrim($info['approver_agent_code'], '0'),$company_id);
            $approved_person_id = $model_m007_employee->getIdByCode(ltrim($info['approved_delegator_code'], '0'),$company_id);
            if($approver_id->first() && $approved_person_id->first()){
                $last_detail_no = $model_t005_approval_target->last_detail_no();
                $last_detail_no == null ? $detail_no = 0 : $detail_no = $last_detail_no->detail_no + 1;

                $model_t005_approval_target->setData($detail_no,$approver_id->first()->employee_id,$approved_person_id->first()->employee_id,$valid_date_start,$valid_date_end_add,$employeeCode->employee_code,$date);
            }
        }
        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * 代理入力者一覧アップロード
     */
    public function uploadAllAgentList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $data = $request->input('data');
        $employeeID = $request->input('employeeID');
        $inputDate = $request->input('inputDate');
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        $valid_date_start = $inputDate;          //追加ユーザの開始日：指定した基準日
        $valid_date_end_add = DATE_SERIAL_MAX;   //追加ユーザの終了日：MAX値
        $valid_date_end_update = $inputDate - 1; //削除ユーザの終了日：指定した基準日の前日

        //現在日時取得
        date_default_timezone_set('Asia/Tokyo');
        $date = date('Y-m-d H:i:s');

        // モデルのインスタンス化 (代理者設定)
        $model_t006_set_input_agent = new t006_set_input_agent();
        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();
        $employeeCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID);

        $model_t006_set_input_agent->updateInputAgentAlluser($valid_date_end_update, $employeeCode->employee_code, $date);
        foreach($data as $info)
        {
            //社員ID取得
            $input_agent_id = $model_m007_employee->getIdByCode(ltrim($info['approver_agent_code'], '0'),$company_id);
            $input_delegator_id = $model_m007_employee->getIdByCode(ltrim($info['approved_delegator_code'], '0'),$company_id);
            if($input_agent_id->first() && $input_delegator_id->first()){
                $last_detail_no = $model_t006_set_input_agent->last_detail_no();
                $last_detail_no == null ? $detail_no = 0 : $detail_no = $last_detail_no->detail_no + 1;

                $model_t006_set_input_agent->setData($detail_no,$input_agent_id->first()->employee_id,$input_delegator_id->first()->employee_id,$valid_date_start,$valid_date_end_add,$employeeCode->employee_code,$date);            }
        }
        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * 有給休暇一括付与一覧アップロード
     */
    public function uploadGrantPaidLeaveList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $data = $request->input('data');
        $employeeID = $request->input('employeeID');
        $inputDate = $request->input('inputDate');
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        //現在日時取得
        date_default_timezone_set('Asia/Tokyo');
        $date = date('Y-m-d H:i:s');

        $model_m007_employee = new m007_employee();
        $model_t009_holiday_management = new t009_holiday_management();
        $model_m033_grant_paid_leave_pattern = new m033_grant_paid_leave_pattern();

        $employee_code = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;

        $holiday_management_info = $model_t009_holiday_management->getAllHolidays();
        $detail_no = count($holiday_management_info);
        for($i = 6; $i < count($data); $i++)
        {
            $valid_date_start = $cf->dateToSerial($data[$i]['valid_date_start']);
            $valid_date_end = $cf->dateToSerial($data[$i]['valid_date_end']);
            $detail_no += 1;

            $valid_date_start_year = $cf->serialToYearNumber($valid_date_start);
            $valid_date_start_month_day = $cf->serialToMonthDayNumber($valid_date_start);

            $next_grant_paid_leave_date_number = ($valid_date_start_year + 1) * 10000 + $valid_date_start_month_day;
            //次回有給付与基準日
            $next_grant_paid_leave_date = $cf->numberToDateSerial($next_grant_paid_leave_date_number);

            //保存休暇移行区分
            $accumulated_paid_leave_transition_class = 0;
            if($data[$i]['holiday_id'] == 1){
                $accumulated_paid_leave_transition_class = 1;
            }
            $employee_data = $model_m007_employee->getEmployeeAll($data[$i]['employee_code'], $company_id);

            $grant_paid_leave_pattern = $model_m033_grant_paid_leave_pattern->getAllBYGrantPaidLeaveDays($employee_data->week_scheduled_working_days,$data[$i]['grant_holiday_days']);
            //年間有給取得義務日数
            if($grant_paid_leave_pattern == null){
                $obligatory_take_paid_leave_days = 0;
            }else{
                $obligatory_take_paid_leave_days = $grant_paid_leave_pattern->obligatory_take_paid_leave_days;
            }

            $model_t009_holiday_management->uploadHoliday($data[$i]['holiday_id'],$employee_data->employee_id,$valid_date_start,$next_grant_paid_leave_date,$data[$i]['grant_holiday_days'],$valid_date_end,$accumulated_paid_leave_transition_class,$obligatory_take_paid_leave_days,$detail_no,$employee_code,$date);
        }

        return response()->json([
            'result' => true,
        ]);

    }


    /**
     * 社員情報一覧アップロード
     */
    public function uploadEmployeeInformationList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $data = $request->input('data');
        $employeeID = $request->input('employeeID');
        $model_m007_employee = new m007_employee();
        $model_m023_work_zone = new m023_work_zone();

        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        $date = $cf->serialToDate($cf->getTodaySerial());

        foreach($data as $info)
        {
            $post_id = $info['post_code'];
            if($post_id == null){
                $post_id = 0;
            }

            $info['work_zone_id'] = $model_m023_work_zone->getIDByCode($info['work_zone_code'],$company_id)->work_zone_id;
            if($info['work_zone_id'] == null){
                $info['work_zone_id'] = 0;
            }

            $model_m007_employee->setEmployeeData(
                $info['employee_id'],
                $info['employee_code'],
                $company_id,
                $info['employee_name'],
                $info['employee_kana_name'],
                $info['gender'],
                $cf->dateToSerial($info['joined_company_date']),
                $cf->dateToSerial($info['retirement_company_date']),
                $info['calendar_id'],
                $info['personal_calendar_id'],
                $info['work_zone_id'],
                $info['week_scheduled_working_days'],
                $cf->getTimeSerial($info['scheduled_working_hours']),
                $cf->getTimeSerial($info['overtime_base_time']),
                $info['available_input_class'],
                $info['close_date_id'],
                $info['grant_paid_leave_pattern_id'],
                $info['authority_pattern_id'],
                $cf->dateToSerial($info['first_paid_leave_date']),
                $info['stamping_target_class'],
                $info['email_address'],
                $cf->dateToSerial($info['grant_starting_date']),
                $info['work_management_target_class'],
                $info['is_delete'],
                $info['thirtysix_agreement_apply_id'],
                $date,
                $userCode);
        }

        return response()->json([
            'result' => true,
        ]);
    }


}
