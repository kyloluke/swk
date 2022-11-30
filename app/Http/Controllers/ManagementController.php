<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\m004_office;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m022_calendar_setting;
use App\Models\m023_work_zone;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t014_office_closing_status;
use App\Models\t015_company_closing_status;
use App\Models\t024_job_state;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\AggregateFunctions;
use App\Jobs\CreateAttendanceTables;
use App\Http\AppLibs\LogFunctions as Log;
use App\Jobs\ResetPasswordAll;
use Exception;

class ManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * パスワード一斉初期化
     */
    public function reset_password_all(Request $request)
    {
        $job_name = "パスワード一斉初期化(実行タイム:".now()->toDateTimeString().")";
        $employee_id = Auth::id();
        $t024_job_state = new t024_job_state;
        $job_state_id = $t024_job_state->createJobEmployee($job_name, $employee_id);
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        ResetPasswordAll::dispatch($job_state_id, $company_id, $employee_id);
        return response()->json([
            'result' => true,
            'values' => [
                'job_state_id' => $job_state_id,
            ]
        ]);
    }

    /**
     * パスワード初期化
     */
    public function reset_password_user(Request $request)
    {
        $employeeCode = $request->input('employeeCode');
        $password = $request->input('password');
        $ret_status = true;
        // パスワードバリデーション
        if($password && ((strlen($password) > 12) || (strlen($password) < 4))){
            //E107-01-005
            $ret_status = false;
            $ret_data = 'パスワードは4～12文字で入力してください';
        } else if(!preg_match('/^[A-Za-z0-9]*$/', $password)){
            //E107-01-006
            $ret_status = false;
            $ret_data = 'パスワードは半角英数のみ使用可能です';
        } else if ($employeeCode == "" || $employeeCode == null) {
            $ret_status = false;
            $ret_data = '入力データが間違っています。入力データをご確認ください。';
        }

        if(!$ret_status) {
            return response()->json([
                'result' => $ret_status,
                'values' => [
                    'message' => $ret_data,
                ]
            ]);
        }
        
        // インスタンス定義
        $m007_employee = new m007_employee();
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        //社員番号からID取得
        $res = $m007_employee->getEmployeeID( $company_id, $employeeCode);
        $employeeId = $res['employee_id'];
        if( $employeeId == null ){
            $ret_status = false;
            $ret_data = '存在しない社員コードです。正しい社員コードを指定してください。';
        }
        if( $ret_status == true ){
            // パスワードが入ってない場合、ランダムパスワード生成しセット
            if( $password == ""  || $password == null ){
                $password = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 6);
            }
            // 新しいパスワードをDBにセット
            $m007_employee->setPassword($employeeId, $password);
            $ret_data[] = [
                'employee_code' => $employeeCode,
                'password' => $password,
            ];
        }
        
        return response()->json([
            'result' => $ret_status,
            'values' => [
                'message' => $ret_data,
            ]
        ]);
    }

    /**
     * 勤務テーブル作成
     */
    public function create_attendance_table(Request $request)
    {
        //対象年月指定
        $target_year_month = $request->input('targetYearMonth');
        
        //6桁かどうか
        if(strlen($target_year_month) != 6)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => "targetYearMonth = " . $target_year_month,
                ]
            ]);
        }
        //年月が妥当かどうか
        try
        {
            $year = intval($target_year_month / 100);
            $month = intval($target_year_month) - $year * 100;
            if($year < 1900 || 2100 < $year || $month < 1 || 12 < $month)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'val' => "targetYearMonth = " . $target_year_month,
                    ]
                ]);
            }
        }
        catch(Exception $e)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => "targetYearMonth = " . $target_year_month,
                ]
            ]);
        }

        //締め区分
        $close_date_id = $request->input('closeDateID');

        if(!($close_date_id == 1 || $close_date_id == 2))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => "closeDateID = " . $close_date_id,
                ]
            ]);
        }

        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        $t024_job_state = new t024_job_state();
        $job_name = "勤務帯作成(year_month = " .  $target_year_month . ", close_date_id = " . $close_date_id . ")";
        $employee_id = Auth::id();
        $job_state_id = $t024_job_state->createJobEmployee($job_name, $employee_id);

        //キューに追加
        CreateAttendanceTables::dispatch($company_id, $target_year_month, $close_date_id, $job_state_id);

        return response()->json([
            'result' => true,
            'values' => [
                'job_state_id' => $job_state_id,
            ]
        ]);
    }
    /**
     * 勤務テーブル作成
     */
    public function create_attendance_table_by_code(Request $request)
    {
        //対象年月指定
        $employee_code = $request->input('employeeCode');
        $target_year_month = $request->input('targetYearMonth');
        //6桁かどうか
        if(strlen($target_year_month) != 6)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => "targetYearMonth = " . $target_year_month,
                ]
            ]);
        }
        //年月が妥当かどうか
        try
        {
            $year = intval($target_year_month / 100);
            $month = intval($target_year_month) - $year * 100;
            if($year < 1900 || 2100 < $year || $month < 1 || 12 < $month)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'val' => "targetYearMonth = " . $target_year_month,
                    ]
                ]);
            }
        }
        catch(Exception $e)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => "targetYearMonth = " . $target_year_month,
                ]
            ]);
        }

        //employee_codeが妥当かどうか
        $employee = m007_employee::where('employee_code', $employee_code)->first();
        if($employee == null || $employee->work_management_target_class == 0)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => '存在しない社員コードです。正しい社員コードを指定してください。',
                ]
            ]);
        }

        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $t014_office_closing_status = new t014_office_closing_status();
        $t015_company_closing_status = new t015_company_closing_status();
        $t024_job_state = new t024_job_state();
        $m004_office = new m004_office();
        $m007_employee = new m007_employee();
        $m016_close_date = new m016_close_date();
        $m022_calendar_setting = new m022_calendar_setting();
        $cf = new CommonFunctions();
        $today_serial = $cf->getTodaySerial();

        if($employee->retirement_company_date < $today_serial){
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => 'すでに退職した社員コードです。正しい社員コードを指定してください。',
                ]
            ]);
        }

        //締め日と対象年月から期間を取得
        $close_date = $employee->close_date->close_date;
        if($close_date == null || $close_date == "")
        {
            $close_date = 0;
        }
        $close_term = $cf->getCloseTerm($target_year_month, $close_date);
        
        $ret_line = implode(",", $close_term);
        $ret_array = explode(",", $ret_line);//結合して再分割するとうまくいく。謎。

        $createStartDate = $ret_array[0];
        $createEndDate = $ret_array[1];
        Log::info(0, 999, "勤務表作成開始 >> " . $cf->serialToDate($createStartDate) . " to " . $cf->serialToDate($createEndDate), $employee->employee_id);

        try
        {
            //t002作成に必要な情報取得
            $employee_id = $employee->employee_id;
            $violation_warning_id = 1;
            $approval_state_id = 1;
            $calendar_id = $employee->calendar_id; //ToDo個人カレンダー対応
            $work_zone = m023_work_zone::find($employee->work_zone_id);
            if($work_zone == null)
            {
                $work_zone_times = [];
                $star_time = 0;
                $end_time = 0;
            }
            else
            {
                $work_zone_times = $work_zone->work_zone_time;
                $star_time = $work_zone_times->first()->start_time;
                $end_time = $work_zone_times->first()->end_time;
            }
            $actual_time = 0;
            $substitute_holiday_reason = "";
            $information = "";
            $remand_reason = "";
            foreach($work_zone_times as $work_zone_time)
            {
                if($work_zone_time->is_delete == 1)
                {
                    continue;
                }
                if($work_zone_time->time_type_class == 1)
                {
                    $actual_time += ($work_zone_time->end_time - $work_zone_time->start_time);
                }
                else
                {
                    $actual_time -= ($work_zone_time->end_time - $work_zone_time->start_time);
                }
            }
            for($target_date = $createStartDate; $target_date <= $createEndDate; $target_date++)
            {
                //カレンダー取得
                $calendar_setting = $m022_calendar_setting->getCalendarSettingByDate($calendar_id, $target_date);
                //日毎に違うデータ
                $attendance_date = $target_date;
                $work_holiday_id = $calendar_setting->work_holiday_id;
                $work_achievement_id = $work_holiday_id == 1 ? 1 : 0;
                $work_zone_id = $work_holiday_id == 1 ? $employee->work_zone_id : 0;
                $work_zone_time_start = $work_holiday_id == 1 ? $star_time : 0;
                $work_zone_time_end = $work_holiday_id == 1 ? $end_time : 0;
                $work_time_start = $work_holiday_id == 1 ? $star_time : 0;
                $work_time_end = $work_holiday_id == 1 ? $end_time : 0;
                $actual_work_time = $work_holiday_id == 1 ? $actual_time: 0;

                if($attendance_date > $employee->retirement_company_date){
                    $work_achievement_id = 8;
                    $work_zone_id = 0;
                    $approval_state_id = 3;
                    $work_zone_time_start = 0;
                    $work_zone_time_end= 0;
                    $work_time_start = 0;
                    $work_time_end = 0;
                    $actual_work_time = 0;
                    $violation_warning_id = 1;
                }
                
                $web_punch_clock_time_start = null;
                $web_punch_clock_time_end = null;
                //Upsert
                $t002_attendance_information->createAttendanceInformationForce(
                    $employee_id,
                    $violation_warning_id,
                    $approval_state_id,
                    $attendance_date,
                    $work_holiday_id,
                    $work_achievement_id,
                    $work_zone_id,
                    $work_zone_time_start,
                    $work_zone_time_end,
                    $work_time_start,
                    $work_time_end,
                    $actual_work_time,
                    $substitute_holiday_reason,
                    $information,
                    $remand_reason,
                    $web_punch_clock_time_start,
                    $web_punch_clock_time_end
                );
            }
            //月の勤務表作成
            $t003_attendance_aggregate->createAttendanceAggregate($employee_id, $target_year_month);
            $ret_array[] = ['employee_id' => $employee_id, 'yearMonth' => $target_year_month];
        }
        catch(Exception $e)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => $e->getMessage()
                ]
            ]);
        }
        Log::info(0, 999, "勤務表作成終了 >> " . $cf->serialToDate($createStartDate) . " to " . $cf->serialToDate($createEndDate), $employee_id);

        return response()->json([
            'result' => true,
            'values' => [
                'employee_id' => $employee_id
            ]
        ]);
    }
    /**
     * jobの実行状態取得
     */
    public function getJobState(Request $request)
    {
        $t024_job_state = new t024_job_state();
        $job_state_id = $request->input('jobStateID');
        if($job_state_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id)',
                ]
            ]);
        }
        $state = $t024_job_state->getJobState($job_state_id);
        // 0 == null　の判定はtrueになってるから、注意必要。
        if($state === null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id not found)',
                ]
            ]);
        }

        // ジョブ処理済み＆ダウンロード必要の場合のケース
        $data = [];
        if($state == 2 && $request->input('csvDownload') && $request->input('type')) {
            $type = $request->input('type');
            switch($type) {
                case 1:
                    $data = $this->resetPasswordDownloadDataInit();
                    break;
                default:
                    
            }
        }

        return response()->json([
            'result' => true,
            'values' => [
                'state' => $state,
                'data' => $data
            ]
        ]);
    }
    /**
     * 指定ジョブへキャンセルフラグを立てる
     */
    public function cancelJob(Request $request)
    {
        $t024_job_state = new t024_job_state();
        $job_state_id = $request->input('jobStateID');
        if($job_state_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id)',
                ]
            ]);
        }

        $state = $t024_job_state->getJobState($job_state_id);
        
        if($state === null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id not found)',
                ]
            ]);
        }
        if(!($state == 0 || $state == 1))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'state' => $state,
                    'message' => 'already stopped',
                ]
            ]);
        }
        //stateを　3 = キャンセルへ変更
        $t024_job_state->cancelJob($job_state_id);
        return response()->json([
            'result' => true,
            'values' => [
            ]
        ]);
    }
    /**
     * T003集計
     */
    public function aggregate_attendance(Request $request)
    {
        //年月（requestから対象年月取得）
        $target_term = $request->input('yearMonth');
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

        //暫定対応
        $start_count = $request->input('start_count');
        $end_count = $request->input('end_count');
        $exec_count = 0;
        
        $m007_employee = new m007_employee();
        $cf = new CommonFunctions();
        $aggregate_func = new AggregateFunctions();
        $model_m016_close_date = new m016_close_date();

        //$target_term = $this->target_term;
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        $employee_array = $m007_employee->getAllEmployeeBelongCompany($company_id);

        $count = 0;
        $failed_array = array();
        foreach($employee_array as $employee)
        {
            if($count < $start_count || $end_count <= $count)
            {
                $count++;
                continue;
            }
            //締め区分を取得
            $close_date_id = m007_employee::find($employee->employee_id)->close_date_id;
            $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
            $close_date = $close_date_info->close_date;
            //締め日を取得
            $close_term = $cf->getCloseTerm($target_term, $close_date);
            $target_start_serial = $close_term['start_sereial'];
            $target_end_serial = $close_term['end_sereial'];

            if(!$aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term))
            {
                $failed_array += array($employee->employee_id);
            }
            $count++;
            $exec_count++;
        }

        //キューに追加
        // AggregateAttendance::dispatch($target_term);

        return response()->json([
            'result' => true,
            'values' => [
                'exec_count' => $exec_count,
                'failed' => $failed_array,
            ]
        ]);
    }
    /**
     * T003集計（社員ID指定）
     */
    public function aggregateAttendanceByID(Request $request)
    {
        //年月（requestから対象年月取得）
        $target_term = $request->input('yearMonth');
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
        $employee_id = $request->input('employeeID');

        $cf = new CommonFunctions();
        $aggregate_func = new AggregateFunctions();
        $model_m016_close_date = new m016_close_date();

        //締め区分を取得
        $employee = m007_employee::find($employee_id);

        if(!$employee) {
            return response()->json([
                'result' => false,
                'values' => [
                    'val' => '存在しない社員IDです。正しい社員IDを指定してください。',
                ]
            ]);
        }
        $close_date_info = $model_m016_close_date->getCloseDates($employee->close_date_id);
        $close_date = $close_date_info->close_date;
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        if(!$aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term))
        {
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        return response()->json([
            'result' => true,
            'values' => [
            ]
        ]);
    }

    /**
     * パスワードリセットした後、CSVダウンロードデータを整理する
     */
    public function resetPasswordDownloadDataInit()
    {
        //会社ID
        $company_id = session()->get('employee')->company_id;
        
        // 必要の場合は分割で処理する
        $employees = m007_employee::where('company_id', $company_id)->orderBy('employee_id')->get();
        $employeeArr = [];
        foreach($employees as $employee) {
            $employeeArr[] = [
                'employee_id' => $employee->employee_id,
                'employee_code' => $employee->employee_code,
                'password' => str_pad($employee->employee_code, 6, '0', STR_PAD_LEFT),
            ];
        }
        return $employeeArr;
    }
}
