<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m030_work_achievement;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t014_office_closing_status;
use App\Models\t015_company_closing_status;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;
use App\Jobs\CloseCompany;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class ClosingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 本人締め
     */
    public function closeThemselves(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //ログイン中の社員情報を取得
        $login_employee_id = $request->session()->get('employee')->employee_id;

        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');
        //検証
        if($employee_id == 0)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください"
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
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください"
                ]
            ]);
        }

        //締め区分を取得
        $close_date_id = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        //t002_勤怠データ取得
        $model_t002_attendance_information = new t002_attendance_information();
        $t002_attendance_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $target_start_serial, $target_end_serial);
        //検証
        if(!$t002_attendance_info)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください"
                ]
            ]);
        }

        //t003_勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);
        //検証
        if(!$t003_attendance_aggregate)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください"
                ]
            ]);
        }
        $model_t004_substitute_information = new t004_substitute_information();
        $substitute_information_info = $model_t004_substitute_information->countUnused($employee_id);
        $attendance_information_info = $model_t002_attendance_information->getDataInmonth($employee_id, $target_start_serial, $target_end_serial);

        $workAchievementDisplayClass5Count = 0;
        foreach($attendance_information_info as $ai_info){
            $m030_work_achievement_info = m030_work_achievement::find($ai_info->work_achievement_id);
            if($m030_work_achievement_info == null){
                continue;
            }
            if($m030_work_achievement_info->work_achievement_display_class == 5){
                $workAchievementDisplayClass5Count += 1;
            }
        }

        //休振の日数<振休の日数の場合
        if($substitute_information_info < $workAchievementDisplayClass5Count){
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "振替休日が振替出勤日よりも多くなっています。振替休日数を振替出勤日数以下にしてください。"
                ]
            ]);
        }

        //t001_打刻データ取得
        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $punch_clock_data = $model_t001_web_punch_clock->getDataWithinTerm($employee_id, $target_start_serial, $target_end_serial);

        $today_serial = $cf->getTodaySerial();
        
        foreach($t002_attendance_info as $info)
        {
            //すべての日付が申請済みであることを確認
            //承認状態IDが「差戻」、「仮申請」がある場合は本人締めできない
            if(($info->approval_state_id == APPROVAL_STATE_REMAND)
            || ($info->approval_state_id == APPROVAL_STATE_TEMPORARY)){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "本人締めエラー。すべての日付が申請されていることを確認してください。"
                    ],
                ]);
            }
            //承認状態IDが「初期状態」
            else if($info->approval_state_id == APPROVAL_STATE_INITIAL){
                //出休IDが「通常勤務」かつ、{乖離なし、かつ、前日以前(自動申請状態)}以外は本人締めできない
                if($info->work_holiday_id == WORK_HOLIDAY_NORMAL){
                    if(!(($info->violation_warning_id == VIOLATION_WARNING_NORMAL) && ($info->attendance_date < $today_serial))){
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "本人締めエラー。すべての日付が申請されていることを確認してください。"
                            ],
                        ]);
                    }
                }
            }

//【暫定】出張など打刻要否をどうするか、が決まるまで、当面は打刻無しで締め可能。打刻無しで締め不可へ変更するときには日毎に打刻がないと申請できないようにする
//            //すべての実働時間がある日付で始業・終業打刻があるか確認
//            if($info->actual_work_time == 0 && $info->midnight_time == 0 && $info->holiday_work_time == 0 && $info->holiday_midnight_work_time == 0){ //実働時間が無い日は打刻チェック対象外
//                continue;
//            }
//
//            $is_punch_clocking_in = false;
//            $is_punch_clocking_out = false;
//            foreach($punch_clock_data as $punch){
//                if($punch->punch_clock_date == $info->attendance_date){
//                    if($punch->clocking_in_out_id == 1){ //出勤
//                        $is_punch_clocking_in = true;
//                        if($is_punch_clocking_in && $is_punch_clocking_out){
//                            break;
//                        }
//                    }
//                    else if($punch->clocking_in_out_id == 2){ //退勤
//                        $is_punch_clocking_out = true;
//                        if($is_punch_clocking_in && $is_punch_clocking_out){
//                            break;
//                        }
//                    }
//                }
//            }
//            if(!($is_punch_clocking_in && $is_punch_clocking_out)){
//                //検証エラー
//                return response()->json([
//                    'result' => false,
//                    'values' => [
//                        'message' => "打刻が無い日があります。すべての日に打刻を入力してください。"
//                    ]
//                ]);
//            }
        }
        
        //締め状態を本人締めに更新
        $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_THEMSELVES);
        $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, CLOSE_STATE_THEMSELVES);
        $model_t003_attendance_aggregate->updateCloseEmployeeId($employee_id, $login_employee_id, $target_term);

        return response()->json([
            'result' => true,
            'values' => [
            ],
        ]);
    }

    /**
     * 管理者締め
     */
    public function closeManager(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //ログイン中の社員情報を取得
        $login_employee_id = $request->session()->get('employee')->employee_id;

        //対象の社員情報を取得
        $employee_id = $request->input('employeeID');
        //検証
        if($employee_id == 0)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
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
                ]
            ]);
        }

        //現在の締め状態を取得
        $close_state_id = $request->input('close_state_id');
        //検証
        if($close_state_id < 2)
        {
            //検証エラー
            return response()->json([
               'result' => false,
               'values' => [
                ]
            ]);
        }

        //締め区分を取得
        $close_date_id = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        //t002_勤怠データ取得
        $model_t002_attendance_information = new t002_attendance_information();
        $t002_attendance_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $target_start_serial, $target_end_serial);
        //検証
        if(!$t002_attendance_info)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //t003_勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);
        //検証
        if(!$t003_attendance_aggregate)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }
        //管理者締め（現在が本人締め）
        if($close_state_id == CLOSE_STATE_THEMSELVES){

            //すべての日付が承認済みかつ本人締め状態であること【保留：申請状態に自動申請が追加されると判定変更が必要】
            $close_flg = true;
            foreach($t002_attendance_info as $info)
            {
                //出休IDが「通常勤務」かつ承認状態IDが「承認済み」であること
                if($info->work_holiday_id == WORK_HOLIDAY_NORMAL){
                    if($info->approval_state_id != APPROVAL_STATE_DONE){
                        $close_flg = false;
                        break;
                    }
                }
                //出休IDが「通常勤務」以外の場合は「承認済み」or「初期状態」であること
                else{
                    if(!($info->approval_state_id == APPROVAL_STATE_INITIAL || $info->approval_state_id == APPROVAL_STATE_DONE)){
                        $close_flg = false;
                        break;
                    }
                }
                //「本人締め」状態であること
                if($info->close_state_id != CLOSE_STATE_THEMSELVES){
                    $close_flg = false;
                    break;
                }
            }

            if($close_flg){
                //締め状態を管理者締めに更新
                $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_MANAGER);
                $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, CLOSE_STATE_MANAGER);
                $model_t003_attendance_aggregate->updateCloseManagerEmployeeId($employee_id, $login_employee_id, $target_term);
            }
        }
        //管理者締め解除（現在が管理者締め）
        else if($close_state_id == CLOSE_STATE_MANAGER){
            $close_flg = true;
            //締め状態を本人締めに更新
            $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_THEMSELVES);
            $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, CLOSE_STATE_THEMSELVES);
            $model_t003_attendance_aggregate->updateCloseManagerEmployeeId($employee_id, 0, $target_term);
        }

        return response()->json([
            'result' => true,
            'close_flg' => $close_flg,
        ]);
    }

    /**
     * 事業所締め状態取得
     */
    public function getOfficeClosingStatus(Request $request)
    {
        //対象の事業所情報を取得
        $office_id = $request->input('officeId');

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        //年月（requestから対象年月取得）
        $target_term = $request->input('targetDate');
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

        //事業所締め状態情報取得
        $model_t014_office_closing_status = new t014_office_closing_status();
        $office_closing_status = $model_t014_office_closing_status->getOfficeClosingStatusWithinTerm($target_term, $close_date_id);
        //検証
        if($office_closing_status->isEmpty())
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        $office_closing_status_array = array();
        foreach($office_closing_status as $info)
        {
            //取得された対象の内、事業所IDが一致するもののみを対象、または、総務からの場合はすべて対象
            if(($office_id) && ($info->office_id != $office_id))
            {
                continue;
            }
            $m004_office = m004_office::find($info->office_id);
            $office_closing_status_array[] = array(
                'office_id' => $info->office_id,
                'office_name' =>$m004_office->office_name,
                'closing_status_class' => $info->closing_status_class,
            );
            continue;
        }

        return response()->json([
            'result' => true,
            'values' => $office_closing_status_array,
        ]);
    }

    /**
     * 事業所締め状態更新
     *  Params 
     *   toState 1：初期状態、4：事業所締め（M019_締め状態の値で指定する）
     */
    public function updateOfficeClosingStatus(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //会社IDを取得
        $company_id = $request->input('companyId');

        //対象の事業所情報を取得
        $office_id = $request->input('officeId');

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;

        //締めるか解除か取得
        $to_state = $request->input('toState');

        //強制締めフラグ
        $is_forced_close = $request->input('is_forced_close');

        //年月（requestから対象年月取得）
        $target_term = $request->input('targetDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $target_term))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエスト検証エラー",
                ]
            ]);
        }
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        //事業所締めデータ取得
        $model_t014_office_closing_status = new t014_office_closing_status();
        $office_closing_status = $model_t014_office_closing_status->getOfficeClosingStatusWithinTerm($target_term, $close_date_id);
        //検証
        if(!$office_closing_status)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "DB検証エラー",
                ]
            ]);
        }
        
        //社員情報取得
        $model_m007_employee = new m007_employee();
        $employee_info = $model_m007_employee->getAllEmployeeBelongCompanyByCloseID($company_id, $close_date_id, $target_end_serial);
        $employee_func = new EmployeeInfoFunctions();

        //勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $model_t002_attendance_information = new t002_attendance_information();

        //強制事業所締め
        if($is_forced_close == "true")
        {
            $model_t014_office_closing_status->updateOfficeClosingStatus($office_id, $target_term, $close_date_id, CLOSE_STATE_OFFICE);

            foreach($employee_info as $info)
            {
                //勤怠締め事業所が一致しない社員を除外
                $employee_data = $employee_func->getEmployeeInfo($info->employee_id, $target_end_serial);
                if($employee_data->work_closing_belonging_office_id != $office_id){
                    continue;
                }

                $model_t003_attendance_aggregate->updateCloseStateId($info->employee_id, $target_term, CLOSE_STATE_OFFICE);
                $model_t002_attendance_information->updateCloseStateId($info->employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_OFFICE);
            }
        }
        //通常事業所締め
        else{
            foreach($employee_info as $info)
            {
                //勤怠締め事業所が一致しない社員を除外
                $employee_data = $employee_func->getEmployeeInfo($info->employee_id, $target_end_serial);
                if($employee_data->work_closing_belonging_office_id != $office_id){
                    continue;
                }

                $attendance_aggregate_info = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($info->employee_id, $target_term);
                //検証
                if(!$attendance_aggregate_info)
                {
                    continue;
                }
                //管理者締めされているか確認
                if($attendance_aggregate_info->close_state_id < CLOSE_STATE_MANAGER)
                {
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "対象のすべての社員が管理者締めされていることを確認してください",
                        ]
                    ]);
                }
            }

            //全社締め状態情報取得
            $model_t015_company_closing_status = new t015_company_closing_status();
            $company_closing_status = $model_t015_company_closing_status->getCompanyClosingStatusWithinTerm($company_id, $target_term, $close_date_id);

            if( (!$company_closing_status)
            || ($company_closing_status->closing_status_class == CLOSE_STATE_COMPANY)     //全社締め済みの場合はエラー
            || ($company_closing_status->closing_status_class == CLOSE_STATE_TO_COMPANY)) //全社締め開始されていたらエラー
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "全社締め済みです",
                    ]
                ]);
            }
            $model_t014_office_closing_status->updateOfficeClosingStatus($office_id, $target_term, $close_date_id, $to_state);

            foreach($employee_info as $info)
            {
                //勤怠締め事業所が一致しない社員を除外
                $employee_data = $employee_func->getEmployeeInfo($info->employee_id, $target_end_serial);
                if($employee_data->work_closing_belonging_office_id != $office_id){
                    continue;
                }

                if($to_state != CLOSE_STATE_OFFICE){
                    $to_state = CLOSE_STATE_MANAGER;
                }
                $model_t003_attendance_aggregate->updateCloseStateId($info->employee_id, $target_term, $to_state);
                $model_t002_attendance_information->updateCloseStateId($info->employee_id, $target_start_serial, $target_end_serial, $to_state);
            }
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => "事業所締め処理が完了しました",
            ]
        ]);
    }

    /**
     * 全社締め状態取得
     */
    public function getCompanyClosingStatus(Request $request)
    {
        //会社IDを取得
        $company_id = $request->input('companyId');

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');

        //年月（requestから対象年月取得）
        $target_term = $request->input('targetDate');
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

        //全社締め状態情報取得
        $model_t015_company_closing_status = new t015_company_closing_status();
        $company_closing_status = $model_t015_company_closing_status->getCompanyClosingStatusWithinTerm($company_id, $target_term, $close_date_id);
        //検証
        if(!$company_closing_status)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //返却用データ
        $company_closing_info = [
            'closing_status_class' => $company_closing_status->closing_status_class,
        ];
        return response()->json([
            'result' => true,
            'values' => $company_closing_info,
        ]);
    }

    /**
     * 全社締め状態更新
     */
    public function updateCompanyClosingStatus(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //会社IDを取得
        $company_id = $request->input('companyId');

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;

        //強制締めフラグ
        $is_forced_close = $request->input('is_forced_close');

        //年月（requestから対象年月取得）
        $target_term = $request->input('targetDate');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $target_term))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエスト検証エラー",
                ]
            ]);
        }

        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        //全社締め状態情報取得
        $model_t015_company_closing_status = new t015_company_closing_status();

        //強制全社締め
        if($is_forced_close == "true")
        {
            $company_closing_status = $model_t015_company_closing_status->getCompanyClosingStatusWithinTerm($company_id, $target_term, $close_date_id);

            if( (!$company_closing_status)
            || ($company_closing_status->closing_status_class == CLOSE_STATE_COMPANY)     //全社締め済みの場合はエラー
            || ($company_closing_status->closing_status_class == CLOSE_STATE_TO_COMPANY)) //全社締め開始されていたらエラー
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "全社締め済みです",
                    ]
                ]);
            }
            //全社締め処理開始
            $model_t015_company_closing_status->updateCompanyClosingStatus($company_id, $target_term, $close_date_id, CLOSE_STATE_TO_COMPANY);
            //締め状態更新処理をキューに追加
            CloseCompany::dispatch($target_start_serial, $target_end_serial, $target_term, $company_id, $close_date_id);
        }
        //通常全社締め
        else{

            //事業所締めデータ取得
            $model_t014_office_closing_status = new t014_office_closing_status();
            $office_closing_status = $model_t014_office_closing_status->getOfficeClosingStatusWithinTerm($target_term, $close_date_id);
            //検証
            if(!$office_closing_status)
            {
                //検証エラー
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "DB検証エラー",
                    ]
                ]);
            }

            foreach($office_closing_status as $info)
            {
                //事業所締め状態か確認
                if($info->closing_status_class != CLOSE_STATE_OFFICE)
                {
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "すべての事業所が締められていることを確認してください",
                        ]
                    ]);
                }
            }
    
            //社員情報取得
            $model_m007_employee = new m007_employee();
            //勤怠集計データ取得
            $model_t003_attendance_aggregate = new t003_attendance_aggregate();

            foreach($office_closing_status as $info)
            {
                $employee_info = $model_m007_employee->getAllEmployeeBelongCompanyByCloseID($company_id, $close_date_id, $target_end_serial);

                foreach($employee_info as $info)
                {
                    $attendance_aggregate_info = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($info->employee_id, $target_term);
                    //検証
                    if(!$attendance_aggregate_info)
                    {
                        continue;
                    }
                    //事業所締めされているか確認
                    if($attendance_aggregate_info->close_state_id < CLOSE_STATE_OFFICE)
                    {
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "すべての事業所が締められていることを確認してください",
                            ]
                        ]);
                    }
                }
            }

            $company_closing_status = $model_t015_company_closing_status->getCompanyClosingStatusWithinTerm($company_id, $target_term, $close_date_id);

            if( (!$company_closing_status)
            || ($company_closing_status->closing_status_class == CLOSE_STATE_COMPANY) //全社締め済みの場合はエラー
            || ($company_closing_status->closing_status_class == CLOSE_STATE_TO_COMPANY)) //全社締め開始されていたらエラー
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "全社締め済みです",
                    ]
                ]);
            }
            //全社締め処理開始
            $model_t015_company_closing_status->updateCompanyClosingStatus($company_id, $target_term, $close_date_id, CLOSE_STATE_TO_COMPANY);
            //締め状態更新処理をキューに追加
            CloseCompany::dispatch($target_start_serial, $target_end_serial, $target_term, $company_id, $close_date_id);
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => "全社締め処理が完了しました",
                'closing_status_class' => CLOSE_STATE_COMPANY,
            ]
        ]);
    }

    /**
     * 社員締め状態取得
     */
    public function getEmployeeClosingStatus(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //会社IDを取得
        $company_id = $request->input('companyId');

        //対象の事業所情報を取得
        $office_id = $request->input('officeId');

        //締め区分を取得
        $close_date_id = $request->input('closeDateId');
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;

        //年月（requestから対象年月取得）
        $target_term = $request->input('targetDate');
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
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_end_serial = $close_term['end_sereial'];

        //社員情報取得
        $model_m007_employee = new m007_employee();

        $employee_info = $model_m007_employee->getAllEmployeeBelongCompanyByCloseID($company_id, $close_date_id, $target_end_serial);

        //検証
        if(!$employee_info)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //所属、役職情報取得
        $model_004_office = new m004_office();
        $m004Office = $model_004_office->getName($office_id);
        $model_m005_dept = new m005_dept();
        $employee_func = new EmployeeInfoFunctions();

        //勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();

        $office_closing_status_array = array();
        foreach($employee_info as $info)
        {
            $employee_data = $employee_func->getEmployeeInfo($info->employee_id, $target_end_serial);

            //勤怠締め事業所が一致しない社員を除外
            if($employee_data->work_closing_belonging_office_id != $office_id){
                continue;
            }

            //所属名を取得
            $office_dept_names = $m004Office->office_name;
            $m005DeptTree = $model_m005_dept->getNameTree($employee_data->dept_id);
            $dept_tree = implode('／', $m005DeptTree);
            $office_dept_names = $office_dept_names . '／' . $dept_tree;

            //勤怠集計データ取得
            $attendance_aggregate_info = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($info->employee_id, $target_term);

            //検証
            if(!$attendance_aggregate_info)
            {
                continue;
            }

            $office_closing_status_array[] = array(
                'employee_id' => $info->employee_id,
                'employee_code' => $info->employee_code,
                'employee_name' => $info->employee_name,
                'post_name' => $employee_data->post_name,
                'office_dept_names' => $office_dept_names,
                'close_state_id' => $attendance_aggregate_info->close_state_id,
            );
            continue;
        }

        return response()->json([
            'result' => true,
            'values' => $office_closing_status_array,
        ]);
    }
}
