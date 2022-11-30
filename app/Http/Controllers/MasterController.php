<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\m001_information_type;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m006_post;
use App\Models\m007_employee;
use App\Models\m012_work_style;
use App\Models\m013_employment_style;
use App\Models\m014_over_time_class;
use App\Models\m015_deduction_reason;
use App\Models\m016_close_date;
use App\Models\m023_work_zone;
use App\Models\m024_work_zone_time;
use App\Models\m027_work_holiday;
use App\Models\m028_web_punch_clock_deviation_time;
use App\Models\m030_work_achievement;
use App\Models\m031_unemployed;
use App\Models\m032_grant_paid_leave_conditions_pattern;
use App\Models\m033_grant_paid_leave_pattern;
use App\Models\m035_36agreement_aggregate_class;
use App\Models\m039_prevention_overwork_check;
use App\Models\m043_holiday;
use App\Models\t002_attendance_information;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;
use App\Models\t003_attendance_aggregate;
use App\Models\m022_calendar_setting;
use Illuminate\Support\Carbon;
use App\Http\AppLibs\LogFunctions as Log;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class MasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function resetMasterData(Request $request)
    {
        $property_name = $request->input('property_name');
        $ret_value = [];
        //以降、必要に応じて作成（LoginControllerと同一処理を呼び出し）
        switch($property_name)
        {
            //勤務帯
            case 'work_zone':
                $mdm023WorkZone = new m023_work_zone();
                $ret_value = $mdm023WorkZone->getWorkZoneList();
                break;
            case 'work_zone_time':
                $mdm024WorkZoneTime = new m024_work_zone_time();
                $ret_value = $mdm024WorkZoneTime->getValidList();
                break;
        }

        return response()->json([
            'result' => true,
            'values' => [
                'name' => $property_name,
                'value' => $ret_value,
            ]
        ]);
    }
    /**
     * 社員情報新規登録
     */
    public function insertEmployeeInformation(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $data = $request->input('data');
        //登録者
        $employeeID = $request->input('employeeID');
        //登録対象者
        $targetEmployeeID = $request->input('targetEmployeeID');
        //対象者旧コード
        $oldTargetEmployeeCode = $request->input('oldTargetEmployeeCode');
        //登録フラグ　新規：true　更新：false
        $isregist = $request->input('isregist');

        $model_m007_employee = new m007_employee();
        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;
        $detail_no = $model_m007_employee->last_detail_no()->detail_no + 1;
        
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        $update_date_serial = $cf->getTodaySerial();
        $update_date = $cf->serialToDate($update_date_serial);

        // バリデーション
        $res = $model_m007_employee->getEmployeeID($company_id,$data[0]['employee_code'])['result'];
        $errMsg = '';
        
        if(!$isregist && $oldTargetEmployeeCode != $data[0]['employee_code'] && $res) {
            // 更新、コードが変更した場合は、コードは重複しているかどうかを判定する
            $errMsg = '社員コード重複しました、再入力してください。';
        } elseif($isregist && $res) {
            // 新規登録、社員コードユニークのチェック
            $errMsg = '社員コードはすでに存在しています、再入力してください。';
        }
        if($errMsg) {
            return response()->json([
                'result' => false,
                'val' => $errMsg
            ]);
        }

        if($isregist){
            //新規
            try {
                $model_m007_employee->insertEmployeeData(
                    $data[0]['employee_code'],
                    $company_id,
                    $data[0]['office_id'],
                    $data[0]['dept_id'],
                    $data[0]['work_closing_belonging_office_id'],
                    $data[0]['post_id'],
                    $data[0]['employee_name'],
                    $data[0]['employee_kana_name'],
                    $data[0]['gender'],
                    $data[0]['joined_company_date'],
                    $data[0]['retirement_company_date'],
                    $data[0]['personal_calendar_id'],
                    $data[0]['work_zone_id'],
                    $data[0]['week_scheduled_working_days'],
                    $data[0]['scheduled_working_hours'],
                    $data[0]['overtime_base_time'],
                    $data[0]['available_input_class'],
                    $data[0]['employment_style_id'],
                    $data[0]['close_date_id'],
                    $data[0]['authority_pattern_id'],
                    $data[0]['first_paid_leave_date'],
                    $data[0]['stamping_target_class'],
                    $data[0]['email_address'],
                    $data[0]['grant_starting_date'],
                    $data[0]['work_management_target_class'],
                    $data[0]['thirtysix_agreement_apply_id'],
                    $data[0]['grant_paid_leave_type_id'],
                    $data[0]['field_work'],
                    $data[0]['deviation_time_before_start_time_id'],
                    $data[0]['deviation_time_after_end_time_id'],
                    0,
                    DATE_SERIAL_MAX,
                    $detail_no,
                    $update_date,
                    $userCode
                );
                $targetEmployeeID = $model_m007_employee->getEmployeeID($company_id, $data[0]['employee_code'])['employee_id'];
                $model_m007_employee->setPassword($targetEmployeeID, str_pad($data[0]['employee_code'], 6, 0, STR_PAD_LEFT));

                //当月、次月までの勤務情報レコーダーを作成する
                $employee = m007_employee::find($targetEmployeeID);
                $this->createAttendanceInfoWhenRegisted($employee);
            }catch(\Exception $e) {
                \Log::info($request->route()->getActionName().'>>>'.$e->getLine().':'.$e->getMessage());
            }
        }else{
            //更新
            $model_m007_employee->updateEmployeeData(
                $targetEmployeeID,
                $data[0]['employee_code'],
                $company_id,
                $data[0]['employee_name'],
                $data[0]['employee_kana_name'],
                $data[0]['gender'],
                $data[0]['joined_company_date'],
                $data[0]['retirement_company_date'],
                $data[0]['personal_calendar_id'],
                $data[0]['work_zone_id'],
                $data[0]['week_scheduled_working_days'],
                $data[0]['scheduled_working_hours'],
                $data[0]['overtime_base_time'],
                $data[0]['available_input_class'],
                $data[0]['close_date_id'],
                $data[0]['authority_pattern_id'],
                $data[0]['first_paid_leave_date'],
                $data[0]['stamping_target_class'],
                $data[0]['email_address'],
                $data[0]['grant_starting_date'],
                $data[0]['work_management_target_class'],
                $data[0]['thirtysix_agreement_apply_id'],
                $data[0]['work_closing_belonging_office_id'],
                $data[0]['office_id'],
                $data[0]['grant_paid_leave_type_id'],
                $data[0]['field_work'],
                $data[0]['deviation_time_before_start_time_id'],
                $data[0]['deviation_time_after_end_time_id'],
                0,
                DATE_SERIAL_MAX,
                $detail_no,
                $update_date,
                $userCode);
        }

        return response()->json([
            'result' => true,
            'values' => $targetEmployeeID,
        ]);
    }

    /**
     * 社員情報取得
     */
    public function getEmployeeInformation(Request $request)
    {
        //登録対象者
        $employeeID = $request->input('employeeID');
        $targetDate = $request->input('targetDate');

        $employee_func = new EmployeeInfoFunctions();
        $employeeData = $employee_func->getEmployeeInfo($employeeID, $targetDate);

        return response()->json([
            'result' => true,
            'values' => $employeeData,
        ]);
    }

    /**
     * 社員情報削除
     */
    public function deleteEmployeeInformation(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        //登録者
        $employeeID = $request->input('employeeID');
        //登録対象者
        $targetEmployeeID = $request->input('targetEmployeeID');

        $model_m007_employee = new m007_employee();
        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;

        $update_date_serial = $cf->getTodaySerial();
        $update_date = $cf->serialToDate($update_date_serial);

        $result = $model_m007_employee->updateIsDelete($targetEmployeeID,$userCode,$update_date);

        return response()->json([
            'result' => $result,
        ]);
    }
    /**
     * マスタ情報を一覧で取得
     */
    public function getOtherMasterList(Request $request)
    {
        $masterID = $request->input('masterID');
        if($masterID == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください",
                ],
            ]);
        }
        //ここでマスタ変更権限確認必要
        $masterClass = null;
        switch($masterID)
        {
            case 1:
                $masterClass = new m001_information_type();
                break;
            case 12:
                $masterClass = new m012_work_style();
                break;
            case 13:
                $masterClass = new m013_employment_style();
                break;
            case 14:
                $masterClass = new m014_over_time_class();
                break;
            case 15:
                $masterClass = new m015_deduction_reason();
                break;
            case 16:
                $masterClass = new m016_close_date();
                break;
            case 27:
                $masterClass = new m027_work_holiday();
                break;
            case 28:
                $masterClass = new m028_web_punch_clock_deviation_time();
                break;
            case 30:
                $masterClass = new m030_work_achievement();
                break;
            case 31:
                $masterClass = new m031_unemployed();
                break;
            case 32:
                $masterClass = new m032_grant_paid_leave_conditions_pattern();
                break;
            case 33:
                $masterClass = new m033_grant_paid_leave_pattern();
                break;
            case 35:
                $masterClass = new m035_36agreement_aggregate_class();
                break;
            case 39:
                $masterClass = new m039_prevention_overwork_check();
                break;
            case 43:
                $masterClass = new m043_holiday();
                break;
            default:
                //これ以外のマスタIDはエラー扱い
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なマスタ種別が指定されました",
                    ],
                ]);
        }
        return response()->json([
            'result' => true,
            'values' => [
                'masterList' => $masterClass->getAllData(),
                'defineList' => $masterClass->getMasterDataDefine(),
            ],
        ]);
    }

    /**
     * 組織マスタ情報を一覧で取得
     */
    public function getOrganizationMasterList(Request $request)
    {
        $company_id = Auth::user()->company_id;

        $masterID = $request->input('masterID');
        if($masterID == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください",
                ],
            ]);
        }
        //ここでマスタ変更権限確認必要
        $masterClass = null;
        switch($masterID)
        {
            case 4:
                $masterClass = new m004_office();
                break;
            case 5:
                $masterClass = new m005_dept();
                break;
            case 6:
                $masterClass = new m006_post();
                break;
            default:
                //これ以外のマスタIDはエラー扱い
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なマスタ種別が指定されました",
                    ],
                ]);
        }
        return response()->json([
            'result' => true,
            'values' => [
                'masterList' => $masterClass->getAllData($company_id),
                'defineList' => $masterClass->getMasterDataDefine(),
            ],
        ]);
    }
    /**
     * マスタデータの更新
     */
    public function updateOtherMasterData(Request $request)
    {
        $masterID = $request->input('masterID');
        if($masterID == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください[id]",
                ],
            ]);
        }
        $masterData = $request->input('masterData');
        if($masterData == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください[data]",
                ],
            ]);
        }
        $masterClass = null;
        switch($masterID)
        {
            case 1:
                $masterClass = new m001_information_type();
                break;
            case 12:
                $masterClass = new m012_work_style();
                break;
            case 13:
                $masterClass = new m013_employment_style();
                break;
            case 14:
                $masterClass = new m014_over_time_class();
                break;
            case 15:
                $masterClass = new m015_deduction_reason();
                break;
            case 16:
                $masterClass = new m016_close_date();
                break;
            case 27:
                $masterClass = new m027_work_holiday();
                break;
            case 28:
                $masterClass = new m028_web_punch_clock_deviation_time();
                break;
            case 30:
                $masterClass = new m030_work_achievement();
                break;
            case 31:
                $masterClass = new m031_unemployed();
                break;
            case 32:
                $masterClass = new m032_grant_paid_leave_conditions_pattern();
                break;
            case 33:
                $masterClass = new m033_grant_paid_leave_pattern();
                break;
            case 35:
                $masterClass = new m035_36agreement_aggregate_class();
                break;
            case 39:
                $masterClass = new m039_prevention_overwork_check();
                break;
            case 43:
                $masterClass = new m043_holiday();
                break;
            default:
                //これ以外のマスタIDはエラー扱い
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なマスタ種別が指定されました",
                    ],
                ]);
        }
        $defineList = $masterClass->getMasterDataDefine();
        //defineに対してkeyの存在とrequreチェック
        $isParamError = false;
        $param = "";
        foreach($defineList as $define)
        {
            $col = $define["column"];
            if($define['required'])
            {
                //必須項目は値なしは非許容
                if(!isset($masterData[$col]) || $masterData[$col] === null || $masterData[$col] === "")
                {
                    //エラー
                    $isParamError = true;
                    break;
                }
            }
            else
            {
                if(!isset($masterData[$col]) || $masterData[$col] === null || trim($masterData[$col]) === "")
                {
                    //必須項目ではない場合、全角空文字をセット
                    $masterData[$col] == "　";
                }
            }
        }
        if($isParamError)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "パラメータに不正なものがあります" . $param,
                ],
            ]);
        }

        //値更新
        $result = $masterClass->updateMasterData($masterData);
        
        if($result)
        {
            return response()->json([
                'result' => true,
                'values' => [
                ],
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "マスタ情報の更新に失敗しました",
                ],
            ]);
        }
    }

    /**
     * 組織マスタデータの更新
     */
    public function updateOrganizationMasterData(Request $request)
    {
        $company_id = Auth::user()->company_id;

        $masterID = $request->input('masterID');
        if($masterID == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください[id]",
                ],
            ]);
        }
        $masterData = $request->input('masterData');
        if($masterData == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください[data]",
                ],
            ]);
        }
        $isNew = $request->input('isNew');
        if($isNew === null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください",
                ],
            ]);
        }
        $masterClass = null;
        switch($masterID)
        {
            case 4:
                $masterClass = new m004_office();
                break;
            case 5:
                $masterClass = new m005_dept();
                break;
            case 6:
                $masterClass = new m006_post();
                break;
            default:
                //これ以外のマスタIDはエラー扱い
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なマスタ種別が指定されました",
                    ],
                ]);
        }
        $defineList = $masterClass->getMasterDataDefine();
        //defineに対してkeyの存在とrequreチェック
        $isParamError = false;
        $param = "";
        foreach($defineList as $define)
        {
            $col = $define["column"];
            if($define['required'])
            {
                //必須項目は値なしは非許容
                if(!isset($masterData[$col]) || $masterData[$col] === null || $masterData[$col] === "")
                {
                    //エラー
                    $isParamError = true;
                    break;
                }
            }
            else
            {
                if(!isset($masterData[$col]) || $masterData[$col] === null || trim($masterData[$col]) === "")
                {
                    //必須項目ではない場合、全角空文字をセット
                    $masterData[$col] == "　";
                }
            }
        }
        if($isParamError)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "パラメータに不正なものがあります" . $param,
                ],
            ]);
        }

        //登録者
        $employeeID = Auth::id();

        $model_m007_employee = new m007_employee();
        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;

        //値更新
        $result = $masterClass->updateOrInsertMasterData($masterData,$isNew,$userCode,$company_id);
        
        if($result)
        {
            return response()->json([
                'result' => true,
                'values' => [
                ],
            ]);
        }
        else
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "マスタ情報の更新に失敗しました",
                ],
            ]);
        }
    }
    /**
     * 退職設定
     */
    public function retirementEmployee(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        //登録者
        $employeeID = Auth::id();
        //登録対象者
        $targetEmployeeID = $request->input('targetEmployeeID');

        $targetEmployee = m007_employee::find($targetEmployeeID);
        if($targetEmployee == null){
            return response()->json([
                'result' => false,
                'message' => "エラー：不正な社員が指定されました。",
            ]);
        }

        //退職年月日
        $retirementDay = $request->input('retirementDay');
        //登録対象者_入社日
        $targetEmployeejoinedCompanyDate = m007_employee::find($employeeID);
        //退職年月日_有効性チェック
        if(!is_numeric($retirementDay)){
            return response()->json([
                'result' => false,
                'message' => "エラー：正しい日付で申請してください。",
            ]);
        }
        if($targetEmployeejoinedCompanyDate->joined_company_date > (int)$retirementDay){
            return response()->json([
                'result' => false,
                'message' => "エラー：退職年月日は入社日以降を指定してください",
            ]);
        }

        $model_m007_employee = new m007_employee();
        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;

        $model_t002_attendance_information = new t002_attendance_information();
        
        $updateDateSerial = $cf->getTodaySerial();

        //退職日の翌日以降を非在籍かつ承認状態に変更。
        $result = $model_t002_attendance_information->updateRetirementDay($employeeID, $targetEmployeeID, $retirementDay, $updateDateSerial);
        if($result){
            //対象者に退職日を設定
            $result = $model_m007_employee->updateIsRetirementDay($targetEmployeeID, $retirementDay, $userCode);
        }
        else{
            return response()->json([
                'result' => $result,
                'message' => "エラー：退職日以降の日付で申請されている日があります。",
            ]);
        }
        
        return response()->json([
            'result' => $result,
            'message' => "",
        ]);

    }

    /**
     * 社員登録と共に勤務情報レコーダーを作成する
     * @param employee 社員情報
     */
    public function createAttendanceInfoWhenRegisted($employee)
    {
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $m022_calendar_setting = new m022_calendar_setting();
        $cf = new CommonFunctions();

        //t002作成に必要な情報取得
        //締め日と対象年月から期間を取得
        $close_date = $employee->close_date->close_date;
        if ($close_date == null || $close_date == "") {
            $close_date = 0;
        }
        $employee_id = $employee->employee_id;
        $violation_warning_id = 1;
        $approval_state_id = 1;
        $calendar_id = $employee->calendar_id; //ToDo個人カレンダー対応
        $work_zone = m023_work_zone::find($employee->work_zone_id);
        if ($work_zone == null) {
            $work_zone_times = [];
            $star_time = 0;
            $end_time = 0;
        } else {
            $work_zone_times = $work_zone->work_zone_time;
            $star_time = $work_zone_times->first()->start_time;
            $end_time = $work_zone_times->first()->end_time;
        }
        $actual_time = 0;
        $substitute_holiday_reason = "";
        $information = "";
        $remand_reason = "";
        foreach ($work_zone_times as $work_zone_time) {
            if ($work_zone_time->is_delete == 1) {
                continue;
            }
            if ($work_zone_time->time_type_class == 1) {
                $actual_time += ($work_zone_time->end_time - $work_zone_time->start_time);
            } else {
                $actual_time -= ($work_zone_time->end_time - $work_zone_time->start_time);
            }
        }

        $target_year_month_arr = [
            Carbon::parse($cf->serialToDate($employee->joined_company_date))->format('Ym'),
            Carbon::parse($cf->serialToDate($employee->joined_company_date))->addMonth()->format('Ym')
        ];

        foreach($target_year_month_arr as $target_year_month)
        {
            $close_term = $cf->getCloseTerm($target_year_month, $close_date);
            $ret_line = implode(",", $close_term);
            $ret_array = explode(",", $ret_line); //結合して再分割するとうまくいく。謎。

            $createStartDate = $ret_array[0];
            $createEndDate = $ret_array[1];
            Log::info(0, 999, "勤務表作成開始 >> " . $cf->serialToDate($createStartDate) . " to " . $cf->serialToDate($createEndDate), $employee->employee_id);

            for ($target_date = $createStartDate; $target_date <= $createEndDate; $target_date++) {
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
                $actual_work_time = $work_holiday_id == 1 ? $actual_time : 0;
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
                    $web_punch_clock_time_end,
                );
            }
            //月の勤務表作成
            $t003_attendance_aggregate->createAttendanceAggregate($employee_id, $target_year_month);
            Log::info(0, 999, "勤務表作成終了 >> " . $cf->serialToDate($createStartDate) . " to " . $cf->serialToDate($createEndDate), $employee_id);
        }
    }
}