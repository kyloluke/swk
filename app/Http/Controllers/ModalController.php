<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Http\AppLibs\CommonFunctions;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t007_over_time_achievement;
use App\Models\t008_unemployed_information;
use App\Models\t010_acquired_holiday;
use App\Models\t011_holiday_worker_information;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m022_calendar_setting;
use App\Models\m024_work_zone_time;
use App\Models\m028_web_punch_clock_deviation_time;
use App\Models\m023_work_zone;
use App\Models\m030_work_achievement;
use App\Models\m031_unemployed;
use App\Http\AppLibs\AggregateFunctions;
use App\Http\AppLibs\ClosingFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;
use App\Models\t014_office_closing_status;
use App\Models\t015_company_closing_status;
use App\Http\AppLibs\LogFunctions as Log;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class ModalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Web打刻一覧取得
     */
    public function getWebPunchList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        // モデルのインスタンス化
        $model_004_office = new m004_office();
        $model_005_dept = new m005_dept();
        $model_m007_employee = new m007_employee();

        // requestから社員ID取得
        $employee_id = $request->input('employeeID');

        //期間（requestから対象年月取得）
        $target_term = $request->input('term');
        //検証
        if(!preg_match('/^([0-9]{6})$/', $target_term))
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => []
            ]);
        }

        //表示に必要なデータ一覧
        //社員番号
        $m007Employee = $model_m007_employee->getEmployeeData($employee_id);
        $employee_code = $m007Employee->employee_code;

        // 締め状態を取得する
        $closeStatus = $this->getCloseStatus($m007Employee, $target_term);
        //名前
        $employee_name = $m007Employee->employee_name;

        //締め日区分
        $close_date_id = $m007Employee->close_date_id;

        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date_name = $close_date_info->close_date_name;
        $close_date = $close_date_info->close_date;
        
        //年月に分解
        $target_term_year = intval($target_term / 100);
        $target_term_month = $target_term - $target_term_year * 100;
        $target_term_date = $close_date;
        
        //期間開始日
        if($close_date == 0)
        {
            //末日締め
            $target_term_start = date('Y-m-d', strtotime('first day of ' . $target_term_year . '-' . $target_term_month));
            $target_term_end = date('Y-m-d', strtotime('last day of ' . $target_term_year . '-' . $target_term_month));
        }
        else
        {
            //締め日の翌日が期間開始日
            $target_term_start = date('Y-m-d', strtotime($target_term_year . '-' . $target_term_month . '-' . ($target_term_date + 1)));
            //終了日は翌月
            $target_term_end = date('Y-m-d', strtotime('+1 month ' . $target_term_year . '-' . $target_term_month . '-' . $target_term_date));
        }
        //シリアル値へ変換
        $target_term_start_serial = $cf->dateToSerial($target_term_start);
        $target_term_end_serial = $cf->dateToSerial($target_term_end);

        //社員情報取得
        $employee_func = new EmployeeInfoFunctions();
        $employee_data = $employee_func->getEmployeeInfo($employee_id, $target_term_start_serial);

        //所属
        $office_id = $employee_data->office_id;
        $m004Office = $model_004_office->getName($office_id);
        $office_name = $m004Office->office_name;
        $dept_id = $employee_data->dept_id;
        //スペース区切りで所属ツリー文字列作成
        $m005DeptTree = $model_005_dept->getNameTree($dept_id);
        $dept_tree = implode('／', $m005DeptTree);
        //事業所にスペース区切りで所属を追加
        $office_name = $office_name . ' ' . $dept_tree;

        //期間内の実績一覧
        $model_t002_attendance_information = new t002_attendance_information();
        $attendance_information_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $target_term_start_serial, $target_term_end_serial);
        
        //名前取得に必要なデータ
        $model_m023_work_zone = new m023_work_zone();
        $model_m030_work_achievement = new m030_work_achievement();
        $model_m031_unemployed = new m031_unemployed();

        //必要なIDをKeyとした名前の配列を取得
        $work_zone_id_array = array();
        $work_archivement_array = array();
        $unemployed_short_name_array = array();
        $unemployed_take_unit_class_array = array();
        foreach($attendance_information_info as $info)
        {
            //勤務帯
            if($info->work_zone_id !== 0 && !array_key_exists($info->work_zone_id, $work_zone_id_array))
            {
                $work_zone_id_array += array($info->work_zone_id => $model_m023_work_zone->getNameByID($info->work_zone_id)->work_zone_name);
            }
            //勤務実績
            if($info->work_achievement_id !== 0 && !array_key_exists($info->work_achievement_id, $work_archivement_array))
            {
                $work_archivement_array += array($info->work_achievement_id => $model_m030_work_achievement->getShortNameByID($info->work_achievement_id)->work_achievement_short_name);
            }
            //不就業略称
            if($info->unemployed_id !== 0 && !array_key_exists($info->unemployed_id, $unemployed_short_name_array))
            {
                $unemployed_short_name_array += array($info->unemployed_id => $model_m031_unemployed->getShortNameByID($info->unemployed_id)->unemployed_short_name);
            }
            //不就業取得単位区分
            if($info->unemployed_id !== 0 && !array_key_exists($info->unemployed_id, $unemployed_take_unit_class_array))
            {
                $unemployed_take_unit_class_array += array($info->unemployed_id => $model_m031_unemployed->getTakeUnitClassByID($info->unemployed_id)->unemployed_take_unit_class);
            }
        }
        
        //期間内のWeb打刻一覧
        //データ取得
        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $punc_clock_data = $model_t001_web_punch_clock->getDataWithinTerm($employee_id, $target_term_start_serial, $target_term_end_serial);

        return response()->json([
            'result'  => true,
            'values' => [
                'employee_code' => $employee_code,
                'employee_name' => $employee_name,
                'office_name' => $office_name,
                'close_date_name' => $close_date_name,
                'close_date' => $close_date,
                'target_term_start' => $target_term_start,
                'target_term_end' => $target_term_end,
                'target_term_start_serial' => $target_term_start_serial,
                'target_term_end_serial' => $target_term_end_serial,
                'puch_clock_data' => $punc_clock_data,
                'attendance_information_data' => $attendance_information_info,
                'workzone_names' => $work_zone_id_array,
                'work_achivement_names'=> $work_archivement_array,
                'unemployed_names'=> $unemployed_short_name_array,
                'unemployed_take_unit_classes'=> $unemployed_take_unit_class_array,
                'is_office_closed' => $closeStatus['is_office_closed'],
                'is_company_closed' => $closeStatus['is_company_closed']
            ]
        ]);
    }
    /**
     * 手入力打刻情報登録
     */
    public function insertUpdateInputPunches(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $model_t002_attendance_information = new t002_attendance_information();
        // requestから社員ID取得
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

        $punch_clock_data_list = $params['punchClockList'];
        $employee_id = $params['employeeID'];

        if($punch_clock_data_list == null || $employee_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }

        foreach($punch_clock_data_list as $info){
            $punch_clock_date = $info['date_serial'];
            if($info['input_punches'][0][0] !== $info['old_input_punches'][0][0]){
                if($info['input_punches'][0][0] !== null){
                    //登録
                    $start_punch_clock_time = intval($info['input_punches'][0][0]);
                    $result = $model_t001_web_punch_clock->upsertInputPunchClock($employee_id, $punch_clock_date, 1, $start_punch_clock_time);
                    if(!$result){
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "手入力出勤打刻登録失敗しました",
                            ]
                        ]);
                    }
                }
                else
                {
                    //削除
                    $result = $model_t001_web_punch_clock->deleteInputWebPunch($employee_id, $punch_clock_date, 1);
                    //他のt001があるならば復活させる
                    $other_web_punch = $model_t001_web_punch_clock->getWebPunchByClockingInOutID($employee_id, $punch_clock_date, 1);
                    if(count($other_web_punch) == 0)
                    {
                        //打刻無しのためt002から削除
                        $model_t002_attendance_information->deleteWebPunchClockTime($employee_id, $punch_clock_date, 1);
                    }
                    else
                    {
                        $buff = 1440;
                        foreach($other_web_punch as $web_punch)
                        {
                            if($buff >= $web_punch->punch_clock_time)
                            {
                                $buff = $web_punch->punch_clock_time;
                            }
                        }
                        //打刻をセット
                        $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($employee_id, $punch_clock_date, 1, $buff, 1);
                    }

                }
            }
            if($info['input_punches'][1][0] !== $info['old_input_punches'][1][0]){
                if($info['input_punches'][1][0] !== null){
                    //登録
                    $end_punch_clock_time = intval($info['input_punches'][1][0]);
                    $result = $model_t001_web_punch_clock->upsertInputPunchClock($employee_id, $punch_clock_date, 2, $end_punch_clock_time);
                    if(!$result){
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "手入力退勤打刻登録失敗しました",
                            ]
                        ]);
                    }
                }
                else
                {
                    //削除
                    $result = $model_t001_web_punch_clock->deleteInputWebPunch($employee_id, $punch_clock_date, 2);
                    //他のt001があるならば復活させる
                    $other_web_punch = $model_t001_web_punch_clock->getWebPunchByClockingInOutID($employee_id, $punch_clock_date, 2);
                    if(count($other_web_punch) == 0)
                    {
                        //打刻無しのためt002から削除
                        $model_t002_attendance_information->deleteWebPunchClockTime($employee_id, $punch_clock_date, 2);
                    }
                    else
                    {
                        $buff = 0;
                        foreach($other_web_punch as $web_punch)
                        {
                            if($buff <= $web_punch->punch_clock_time)
                            {
                                $buff = $web_punch->punch_clock_time;
                            }
                        }
                        //打刻をセット
                        $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($employee_id, $punch_clock_date, 2, $buff, 1);
                    }
                }
            }
        }

        $ret = Artisan::call('batch:transferwebpunch');

        $model_t002_attendance_information = new t002_attendance_information();
        //違反警告ID(乖離)
        foreach($punch_clock_data_list as $info){
            if($info['input_punches'][0][0] === $info['old_input_punches'][0][0] && $info['input_punches'][1][0] === $info['old_input_punches'][1][0]){
                continue;
            }
            $punch_clock_date = $info['date_serial'];
            $attendance_information_info = $model_t002_attendance_information->getAttendanceInformationByDate($employee_id, $punch_clock_date);

            if($attendance_information_info == null){
                continue;
            }

            if($punch_clock_date >= $cf->getTodaySerial()){
                break;
            }
            if($attendance_information_info->work_holiday_id == 1){
                //申請状態が初期状態の以外の場合は更新しない
                if($attendance_information_info->approval_state_id != 1)
                {
                    continue;
                }
                //勤務帯が登録されていない場合は判定しない
                if($attendance_information_info->work_zone_id == 0)
                {
                    continue;
                }

                //手入力打刻ありので乖離
                $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $punch_clock_date, 2);
            }else if(($attendance_information_info->work_holiday_id == 2 || $attendance_information_info->work_holiday_id == 3) && ($attendance_information_info->work_achievement_id == 0 || $attendance_information_info->work_achievement_id == 9)){
                //申請状態が初期状態の以外の場合は更新しない
                if($attendance_information_info->approval_state_id != 1)
                {
                    continue;
                }
                //振替休日と会社休日で打刻ありので乖離
                $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $punch_clock_date, 2);
            }
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => "手入力打刻登録しました",
            ]
        ]);
    }

     /**
     * 手入力打刻情報登録（当日）
     */
    public function insertUpdateTodayInputPunch(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $model_t002_attendance_information = new t002_attendance_information();
        // requestから社員ID取得
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

        $today_punch_clock_date = $params['today_punch_clock_date'];
        $today_punch_clock_time_in = $params['today_punch_clock_time_in'];
        $today_punch_clock_time_out = $params['today_punch_clock_time_out'];
        $old_today_punch_clock_time_in = $params['old_today_punch_clock_time_in'];
        $old_today_punch_clock_time_out = $params['old_today_punch_clock_time_out'];
        $employee_id = $params['employee_id'];

        if($today_punch_clock_date == null || $employee_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        if($today_punch_clock_time_in !== $old_today_punch_clock_time_in){
            if($today_punch_clock_time_in !== null){
                //登録
                $start_punch_clock_time = intval($today_punch_clock_time_in);
                $result = $model_t001_web_punch_clock->upsertInputPunchClock($employee_id, $today_punch_clock_date, 1, $start_punch_clock_time);
                if(!$result){
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "手入力出勤打刻登録失敗しました",
                        ]
                    ]);
                }
            }
            else
            {
                //削除
                $result = $model_t001_web_punch_clock->deleteInputWebPunch($employee_id, $today_punch_clock_date, 1);
                //他のt001があるならば復活させる
                $other_web_punch = $model_t001_web_punch_clock->getWebPunchByClockingInOutID($employee_id, $today_punch_clock_date, 1);
                if(count($other_web_punch) == 0)
                {
                    //打刻無しのためt002から削除
                    $model_t002_attendance_information->deleteWebPunchClockTime($employee_id, $today_punch_clock_date, 1);
                }
                else
                {
                    $buff = 1440;
                    foreach($other_web_punch as $web_punch)
                    {
                        if($buff >= $web_punch->punch_clock_time)
                        {
                            $buff = $web_punch->punch_clock_time;
                        }
                    }
                    //打刻をセット
                    $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($employee_id, $today_punch_clock_date, 1, $buff, 1);
                }
            }
        }
        if($today_punch_clock_time_out !== $old_today_punch_clock_time_out){
            if($today_punch_clock_time_out !== null){
                //登録
                $end_punch_clock_time = intval($today_punch_clock_time_out);
                $result = $model_t001_web_punch_clock->upsertInputPunchClock($employee_id, $today_punch_clock_date, 2, $end_punch_clock_time);
                if(!$result){
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "手入力退勤打刻登録失敗しました",
                        ]
                    ]);
                }
            }
            else
            {
                //削除
                $result = $model_t001_web_punch_clock->deleteInputWebPunch($employee_id, $today_punch_clock_date, 2);
                //他のt001があるならば復活させる
                $other_web_punch = $model_t001_web_punch_clock->getWebPunchByClockingInOutID($employee_id, $today_punch_clock_date, 2);
                if(count($other_web_punch) == 0)
                {
                    //打刻無しのためt002から削除
                    $model_t002_attendance_information->deleteWebPunchClockTime($employee_id, $today_punch_clock_date, 2);
                }
                else
                {
                    $buff = 0;
                    foreach($other_web_punch as $web_punch)
                    {
                        if($buff <= $web_punch->punch_clock_time)
                        {
                            $buff = $web_punch->punch_clock_time;
                        }
                    }
                    //打刻をセット
                    $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($employee_id, $today_punch_clock_date, 2, $buff, 1);
                }
            }
        }

        $ret = Artisan::call('batch:transferwebpunch');

        $model_t002_attendance_information = new t002_attendance_information();
        //違反警告ID(乖離)

        if($today_punch_clock_time_in === $old_today_punch_clock_time_in && $today_punch_clock_time_out === $old_today_punch_clock_time_out){
            return response()->json([
                'result' => true,
                'values' => [
                    'message' => "手入力打刻登録しました",
                ]
            ]);
        }
        $attendance_information_info = $model_t002_attendance_information->getAttendanceInformationByDate($employee_id, $today_punch_clock_date);

        if($attendance_information_info == null){
            return response()->json([
                'result' => true,
                'values' => [
                    'message' => "手入力打刻登録しました",
                ]
            ]);
        }
        if($attendance_information_info->work_holiday_id == 1){
            //申請状態が初期状態の以外の場合は更新しない
            if($attendance_information_info->approval_state_id != 1)
            {
                return response()->json([
                    'result' => true,
                    'values' => [
                        'message' => "手入力打刻登録しました",
                    ]
                ]);
            }
            //勤務帯が登録されていない場合は判定しない
            if($attendance_information_info->work_zone_id == 0)
            {
                return response()->json([
                    'result' => true,
                    'values' => [
                        'message' => "手入力打刻登録しました",
                    ]
                ]);
            }

            //手入力打刻ありので乖離
            $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $today_punch_clock_date, 2);
        }else if(($attendance_information_info->work_holiday_id == 2 || $attendance_information_info->work_holiday_id == 3) && ($attendance_information_info->work_achievement_id == 0 || $attendance_information_info->work_achievement_id == 9)){
            //申請状態が初期状態の以外の場合は更新しない
            if($attendance_information_info->approval_state_id != 1)
            {
                return response()->json([
                    'result' => true,
                    'values' => [
                        'message' => "手入力打刻登録しました",
                    ]
                ]);
            }
            //振替休日と会社休日で打刻ありので乖離
            $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee_id, $today_punch_clock_date, 2);
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => "手入力打刻登録しました",
            ]
        ]);
    }

    /**
     * 日毎の詳細情報を取得
     */
    public function getDailyInformation(Request $request)
    {
        //対象の社員情報を取得
        $attendance_information_id = $request->input('attendanceInformationId');
        $attendance_information = t002_attendance_information::find($attendance_information_id);
        if($attendance_information == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'attendance_information_id' => $attendance_information_id,
                ]
            ]);
        }
        //リレーション先の情報取得
        $attendance_information->employee;
        $attendance_information->employee->deviation_time_after_end_time;
        $attendance_information->violation_warning;
        $attendance_information->work_holiday;
        $attendance_information->approval_state;
        $attendance_information->work_achievement;
        $attendance_information->work_zone;
        if($attendance_information->work_zone)
        {
            $attendance_information->work_zone->work_zone_time;
        }
        $attendance_information->over_time_achievement;
        $attendance_information->unemployed_information;
        $attendance_information->acquired_holiday;
        $attendance_information->holiday_worker_information;
        //当日・前後2日のWeb打刻を取得
        $target_term_start_serial = $attendance_information->attendance_date - 1;
        $target_term_end_serial = $attendance_information->attendance_date + 1;
        $employee_id = $attendance_information->employee->employee_id;
        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $attendance_information['web_punch_clock_list'] = $model_t001_web_punch_clock->getDataWithinTerm($employee_id, $target_term_start_serial, $target_term_end_serial);
        //対象社員のその日の戻り打刻一番遅い時刻を取得する
        $last_come_back_punched_time = $model_t001_web_punch_clock->getLastComeBackPunchedTime($employee_id, $attendance_information->attendance_date, 4);
        $attendance_information['last_come_back_punched_time'] = $last_come_back_punched_time ? $last_come_back_punched_time->punch_clock_time : '';
        //登録済の不就業情報取得
        $t008_unemployed_information = new t008_unemployed_information();
        $attendance_information['unemployed_array'] = $t008_unemployed_information->getUnployedInformation($attendance_information_id);
        //登録済みの時間外取得
        $t007_over_time_achievement = new t007_over_time_achievement();
        $attendance_information['over_time_class_array'] = $t007_over_time_achievement->getOverTimeAchievementInformation($attendance_information_id);
        //登録済みの振替休日取得
        $t004_substitute_information = new t004_substitute_information();
        $attendance_information['substitute_information'] = $t004_substitute_information->getSubstituteHolidayByAttendanceInformationID($attendance_information_id);

        // 締め状態を取得する
        $cf = new CommonFunctions();
        $closeStatus = $this->getCloseStatus($attendance_information->employee, $cf->serialToDateStr($attendance_information->attendance_date, 'Ym'));
        $attendance_information['is_company_closed'] = $closeStatus['is_company_closed'];
        $attendance_information['is_office_closed'] = $closeStatus['is_office_closed'];
        return response()->json([
            'result' => true,
            'values' => [
                'attendance_information' => $attendance_information,
            ]
        ]);
    }
    /**
     * 日毎の詳細情報を登録
     */
    public function saveDailyInformation(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
        $aggregate_func = new AggregateFunctions();
        $closingFunc = new ClosingFunctions();
        //使用するDBインスタンス作成
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $t004_substitute_information = new t004_substitute_information();
        $t008_unemployed_information = new t008_unemployed_information();
        $t007_over_time_achievement = new t007_over_time_achievement();
        $t010_acquired_holiday = new t010_acquired_holiday();
        $t011_holiday_worker_information= new t011_holiday_worker_information();
        $m022_calendar_setting = new m022_calendar_setting();
        $model_m024_work_zone_time = new m024_work_zone_time();

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
        $info = $params['info'];
        $type = $params['type'];
        if($info == null || $type == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        //infoをBASE64デコードしてJSONへ
        $info = json_decode(base64_decode($info), true);
        //処理結果保持
        $result = false;
        $message = '';
        //申請者社員ID
        $employee = $request->session()->get('employee');
        if($employee == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $login_employee_id = $employee->employee_id;

        if(!isset($info['employee_id']))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "登録処理に失敗しました。ページを再度読み込みなおすか、再ログインを行ってください。",
                ],
            ]);
        }
        //対象社員情報
        $employee = m007_employee::find($info['employee_id']);

        //締め区分を取得
        $close_date_id = m007_employee::find($employee->employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        
        //日付と締め区分から対象年月を取得
        $target_term = $cf->getTargetTerm($info['attendance_date'], $close_date);
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];
                 
        switch($type)
        {
            case 1://申請
            case 5://仮申請

                // 実績は振休 || (不就業なし && 実績なし)
                if($info['work_achievement_id'] === 9 || ($info['unemployed_id'] === 0 && $info['work_achievement_id'] === 0)){

                    //現在日時取得
                    date_default_timezone_set('Asia/Tokyo');
                    $updated_at = date('Y-m-d H:i:s');
                    $updated_user = $login_employee_id;
                    $information = "";
                    if(isset($info['information'])){
                        $information = $info['information'];
                    }

                    //実績情報を更新
                    if($type == 1){
                        $t002_attendance_information->updateApprovalStateIdAndInformation($info['attendance_information_id'],$updated_user,$updated_at,$information);
                    }else{
                        $t002_attendance_information->updateAssumedApprovalStateIdAndInformation($info['attendance_information_id'],$updated_user,$updated_at,$information);
                    }

                    if($type == 1){
                        Log::info(2, 2, "申請実施（振替休日・不就業）", $employee->employee_id);
                    }else{
                        Log::info(2, 2, "仮申請実施（振替休日・不就業）", $employee->employee_id);
                    }

                    Log::info(2, 2, "本人締め自動化処理 申請実施（振替休日・不就業）", $employee->employee_id);
                    $closingFunc->autoCloseThemSelves($login_employee_id, $info['employee_id'], $target_term, false);
                    
                    //完了
                    $result = true;
                    break;
                }
                //振替休日の申請時のみ先にチェック
                if($info['work_achievement_id'] === 7)
                {
                    //カレンダ設定
                    $key_index = array_search($info['substitute_holiday_date'], array_column($employee->calendar->calendar_setting->toArray(), 'calendar_date'));
                    if($key_index)
                    {
                        //振替先が通常日かどうか
                        if($employee->calendar->calendar_setting[$key_index]['work_holiday_id'] != 1)
                        {
                            //振替NG
                            return response()->json([
                                'result' => false,
                                'values' => [
                                    'message' => "振替休日の対象に休日が指定されています。通常勤務日を指定してください。",
                                ],
                            ]);
                            return;
                        }
                    }
                    else
                    {
                        //カレンダー見つからない
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' =>  "指定した日付は振替休日として選択できません",
                            ],
                        ]);
                    }
                    //振替休日指定なし
                    if($info['substitute_holiday_date'] == null || $info['substitute_holiday_date'] == 0)
                    {
                        //取り消しの場合、既に振替休日を取得済みの時、取り消しは不可
                        $sub_infor_by_id = $t004_substitute_information->getSubstituteHolidayByAttendanceInformationID($info['attendance_information_id']);
                        if($sub_infor_by_id != null && count($sub_infor_by_id) != 0)
                        {
                            if($sub_infor_by_id['acquired_substitue_holiday_date'] != 0)
                            {
                                //取得済みのためエラー
                                return response()->json([
                                    'result' => false,
                                    'values' => [
                                        'message' => "既に振替予定先に勤務情報が登録されています。振替休日情報を変更できません。",
                                    ],
                                ]);
                            }
                        }
                    }
                    //振替休日指定あり
                    else
                    {
                        //振替対象が初期状態以外の時は、指定不可
                        $target_date_info = $t002_attendance_information->getAttendanceInformationByDate($employee->employee_id, $info['substitute_holiday_date']);
                        if($target_date_info != null)
                        {
                            if($target_date_info->approval_state_id != 1)
                            {
                                //振替NG
                                return response()->json([
                                    'result' => false,
                                    'values' => [
                                        'message' => "振替休日の対象に既に申請もしくは承認が行われています。他の勤務日を指定してください。",
                                    ],
                                ]);
                            }
                        }
                        //振替休日予定日は 当月であれば指定可とする（前月以前は指定不可）
                        $attendanceDateSerialOfMonthFirstDay = $cf->dateToSerial($cf->serialToDateStr($info['attendance_date'], 'Y-m') . '-01');
                        if($info['substitute_holiday_date'] < $attendanceDateSerialOfMonthFirstDay)
                        {
                            //振替予定日NG
                            return response()->json([
                                'result' => false,
                                'values' => [
                                    'message' => "振替休日は当月以降を選択してください。他の勤務日を指定してください。",
                                ],
                            ]);
                        }
                        //一覧取得
                        $substitute_information = $t004_substitute_information->getSubstituteHolidayDate($employee->employee_id);
                        
                        if($substitute_information != null && count($substitute_information) != 0)
                        {
                            //既に他の振替日に設定されていないか
                            //振替対象、もしくは振替済みに一致しするものを探す
                            //振替対象日
                            foreach($substitute_information as $sub)
                            {
                                if($sub->substitute_holiday_date == $info['substitute_holiday_date'] || $sub->acquired_substitue_holiday_date == $info['substitute_holiday_date'])
                                {
                                    //見つかった
                                    //申請日当日（上書き）でない場合はNG
                                    if($sub->attendance_information_id != $info['attendance_information_id'])
                                    {
                                        //振替NG
                                        return response()->json([
                                            'result' => false,
                                            'values' => [
                                                'message' => "指定した振替休日予定日に他の振替休日予定もしくは実績が登録されています",
                                            ],
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                //ここまでで振替対象チェック完了
                //不就業がある場合、条件取得
                for($i = 0; $i < count($info['unemployed_array_valid']); $i++)
                {
                    $info['unemployed_array_valid'][$i]['unemployed_info'] = m031_unemployed::find($info['unemployed_array_valid'][$i]['unemployed_id']);
                }
                //不就業がある場合、実働や休憩から引く
                if(0 < count($info['unemployed_array_valid']))
                {
                    //不就業時間合計
                    if($info['exclude_actual_work_time'])
                    {
                        //実働もしくは休日実働から引く
                        $info['actual_work_time'] = $info['actual_work_time'] - $info['exclude_actual_work_time'] < 0 ? 0 : $info['actual_work_time'] - $info['exclude_actual_work_time'];
                        $info['holiday_work_time'] = $info['holiday_work_time'] - $info['exclude_actual_work_time'] < 0 ? 0 : $info['holiday_work_time'] - $info['exclude_actual_work_time'];
                    }
                    if($info['exclude_rest_time'])
                    {
                        //休憩もしくは休日休憩から引く
                        $info['break_time'] = $info['break_time'] - $info['exclude_rest_time'] < 0 ? 0 : $info['break_time'] - $info['exclude_rest_time'];
                        $info['holiday_work_break_time'] = $info['holiday_work_break_time'] - $info['exclude_rest_time'] < 0 ? 0 : $info['holiday_work_break_time'] - $info['exclude_rest_time'];
                    }
                    if($info['exclude_midnight_actual_work_time'])
                    {
                        //深夜実働もしくは休日深夜実働から引く
                        $info['midnight_time'] = $info['midnight_time'] - $info['exclude_midnight_actual_work_time'] < 0 ? 0 : $info['midnight_time'] - $info['exclude_midnight_actual_work_time'];
                        $info['holiday_midnight_work_time'] = $info['holiday_midnight_work_time'] - $info['exclude_midnight_actual_work_time'] < 0 ? 0 : $info['holiday_midnight_work_time'] - $info['exclude_midnight_actual_work_time'];
                    }
                    if($info['exclude_midnight_rest_time'])
                    {
                        //深夜休憩もしくは休日深夜休憩から引く
                        $info['midnight_break_time'] = $info['midnight_break_time'] - $info['exclude_midnight_rest_time'] < 0 ? 0 : $info['midnight_break_time'] - $info['exclude_midnight_rest_time'];
                        $info['holiday_midnight_work_break_time'] = $info['holiday_midnight_work_break_time'] - $info['exclude_midnight_rest_time'] < 0 ? 0 : $info['holiday_midnight_work_break_time'] - $info['exclude_midnight_rest_time'];
                    }
                }
                //時間外時間を実働や休憩へ足す
                if(0 < count($info['over_time_class_array_valid']))
                {
                    if($info['additional_actual_work_time'])
                    {
                        //実働もしくは休日実働へ足す
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_work_time'] += $info['additional_actual_work_time'];
                        }
                        else
                        {
                            $info['actual_work_time'] += $info['additional_actual_work_time'];
                        }
                    }
                    if($info['additional_break_time'])
                    {
                        //休憩もしくは休日休憩へ足す
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_work_break_time'] += $info['additional_break_time'];
                        }
                        else
                        {
                            $info['break_time'] += $info['additional_break_time'];
                        }
                    }
                    if($info['additional_midnight_time'])
                    {
                        //深夜実働もしくは休日深夜実働へ足す
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_midnight_work_time'] += $info['additional_midnight_time'];
                        }
                        else
                        {
                            $info['midnight_time'] += $info['additional_midnight_time'];
                        }
                    }
                    if($info['additional_midnight_break_time'])
                    {
                        //深夜休憩もしくは休日深夜休憩から引く
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_midnight_work_break_time'] += $info['additional_midnight_break_time'];
                        }
                        else
                        {
                            $info['midnight_break_time'] += $info['additional_midnight_break_time'];
                        }
                    }
                }
                //実働時間がマイナスになっている場合は休憩指定など入力がおかしいのでエラー
                if($info['actual_work_time'] < 0 || $info['holiday_work_time'] < 0 || $info['break_time'] < 0 || $info['holiday_work_break_time'] < 0 || 
                    $info['midnight_time'] < 0 || $info['holiday_midnight_work_time']< 0 || $info['midnight_break_time'] < 0 || $info['holiday_midnight_work_break_time'] < 0)
                {
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "時間外時間もしくは不就業時間の入力により、実働時間が計算できない項目があります",
                        ],
                    ]);
                }

                //法定内時間外の再計算
                $actual_and_midnight_work_time = $info['actual_work_time'] + $info['midnight_time'];
                if(isset($info['additional_overtime_in_unemployed']))
                {
                    $actual_and_midnight_work_time += $info['additional_overtime_in_unemployed'];
                }
                if($actual_and_midnight_work_time <= $info['employee']['overtime_base_time'])
                {
                    $info['statutory_working_time'] = 0;
                }
                else if($actual_and_midnight_work_time <= 8 * 60)
                {
                    $info['statutory_working_time'] = $actual_and_midnight_work_time - $info['employee']['overtime_base_time'];
                }
                else
                {
                    $info['statutory_working_time'] = 8 * 60 - $info['employee']['overtime_base_time'];
                }
                //法定外時間外の再計算
                $info['non_statutory_working_time'] = 8 * 60 < $actual_and_midnight_work_time ? $actual_and_midnight_work_time - 8 * 60 : 0;

                //以降は他の計算完了後に実施
                //実働時間を再計算
                if(isset($info['work_achievement']) && $info['work_achievement']['work_achievement_display_class'] == 3)
                {
                    //休日時間を実働時間に計上
                    $info['actual_work_time'] = $info['holiday_work_time'] + $info['holiday_midnight_work_time'];
                }
                else
                {
                    //深夜時間を算入
                    $info['actual_work_time'] = $info['actual_work_time'] + $info['midnight_time'];
                }

                //nullのままだとエラー出るので0を代入
                if($info['midnight_time'] == null)
                {
                    $info['midnight_time'] = 0;
                }

                //申請者情報と申請日をセット
                $info['approval_request_date'] = $cf->getTodaySerial();
                $info['input_employee_id'] = $login_employee_id;
                $info['approval_employee_id'] = 0; //承認者はリセット
                $info_substitute_holiday_reason = "";
                if($info['work_achievement_id'] == 7){
                    $info_substitute_holiday_reason = "休日勤務(" . $cf->serialToDate($info['attendance_date']) . ")分の振替休日取得";
                }

                if(isset($info['substitute_holiday_date'])){
                    if($info['substitute_holiday_date'] != 0){
                        //登録された振替休日取得
                        $t004_substitute_information_info = $t004_substitute_information->getSubstituteHolidayByAttendanceInformationID($info['attendance_information_id']);
                        if($t004_substitute_information_info != null){
                            //振休が登録されている場合取り消し
                            $t002_attendance_information->updateSubstituteHoliday($info['employee_id'], $t004_substitute_information_info->substitute_holiday_date, $info['work_zone_id'], $info['work_time_start'], $info['work_time_end'], $info['actual_work_time']);
                        }
                    }
                }
                                
                //実績情報を更新
                if($type == 1){
                    $t002_attendance_information->applyAttendanceInformation($info, 2, $info_substitute_holiday_reason);
                }else{
                    $t002_attendance_information->applyAttendanceInformation($info, 5, $info_substitute_holiday_reason);
                }
                //不就業情報更新
                $t008_unemployed_information->applyUnemployedInformation($info);
                //時間外情報更新
                $t007_over_time_achievement->applyOverTimeAchievementInformation($info);
                //振替休日更新
                $t004_substitute_information->applySubsttuteHolidayDate($info, $info_substitute_holiday_reason);
                //休暇取得情報登録
                $t010_acquired_holiday->applyAcauiredHoliday($info);
                //休日出勤者情報登録
                $t011_holiday_worker_information->applyHolidayWorkerInformation($info);

                //T003更新
                $is_updated = $aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term);
                if(!$is_updated){
                    Log::error(2, 2, "AggregateFunctions", $employee->employee_id);
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "集計に失敗しました。システム管理者へ確認を行ってください",
                        ],
                    ]);
                }

                if($type == 1){
                    Log::info(2, 2, "申請実施", $employee->employee_id);
                    // 本人締め自動化処理呼び出し
                    Log::info(2, 2, "本人締め自動化処理 申請実施", $employee->employee_id);
                }else{
                    Log::info(2, 2, "仮申請実施", $employee->employee_id);
                }

                $closingFunc->autoCloseThemSelves($login_employee_id, $info['employee_id'], $target_term, false);

                //完了
                $result = true;
                break;
            case 2:
                //承認
                //承認状態へ変更
                Log::info(2, 2, "承認実施", $employee->employee_id);
                $t002_attendance_information->approve($info, $login_employee_id);

                //管理者締め自動化処理呼び出し
                $closeStateId = $params['closeStateId'];
                Log::info(2, 2, "管理者締め自動化処理 承認", $employee->employee_id);
                $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, $closeStateId);
                $result = true;
                break;
            case 3:
                //差戻

                //締め状態を初期状態に戻す
                $t003_attendance_aggregate->updateCloseStateId($employee->employee_id, $target_term, CLOSE_STATE_INITIAL);
                $t002_attendance_information->updateCloseStateId($employee->employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_INITIAL);

                Log::info(2, 2, "差戻実施", $employee->employee_id);
                $t002_attendance_information->remand($info);
                $result = true;
                break;
            case 4:
                //申請取り消し
                //対象社員ID

                $employee_id = $employee->employee_id;
                $closeStateId = $params['closeStateId'];
                if ($info['work_achievement_id'] === 9) {
                    //現在日時取得
                    date_default_timezone_set('Asia/Tokyo');
                    $updated_at = date('Y-m-d H:i:s');
                    $updated_user = $login_employee_id;

                    $t002_attendance_information->cancelApprovalStateIdAndInformation($info['attendance_information_id'], $updated_user, $updated_at);

                    Log::info(2, 2, "申請取り消し実施 （振替休日）", $employee_id);
                    // 本人締め自動化処理呼び出し
                    Log::info(2, 2, "本人締め自動化処理 申請取り消し（振替休日）", $employee->employee_id);
                    $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, $closeStateId);
                    $result = true;
                    break;
                }

                //勤務帯から始業時間、終業時間、実働時間を算出
                $calendar_id = $employee->calendar_id; //ToDo個人カレンダー対応
                $work_zone = m023_work_zone::find($employee->work_zone_id);
                $actual_time = 0;
                $start_time = 0;
                $end_time = 0;
                if ($work_zone != null) {
                    $work_zone_times_work = $model_m024_work_zone_time->getStartEndTime($employee->work_zone_id, 1);
                    $work_zone_times_rests = $model_m024_work_zone_time->getStartEndList($employee->work_zone_id, 2);
                    if ($work_zone_times_work) {
                        $start_time = $work_zone_times_work->start_time;
                        $end_time = $work_zone_times_work->end_time;
                        $actual_time += ($work_zone_times_work->end_time - $work_zone_times_work->start_time);
                    }
                    foreach ($work_zone_times_rests as $work_zone_times_rest) {
                        $actual_time -= ($work_zone_times_rest->end_time - $work_zone_times_rest->start_time);
                    }
                }

                //カレンダー取得
                $calendar_setting = $m022_calendar_setting->getCalendarSettingByDate($calendar_id, $info['attendance_date']);
                //日毎に違うデータ
                $attendance_date = $info['attendance_date'];
                $work_holiday_id = $calendar_setting->work_holiday_id;
                $work_achievement_id = $work_holiday_id == 1 ? 1 : 0;
                $work_zone_id = $work_holiday_id == 1 ? $employee->work_zone_id : 0;
                $work_time_start = $work_holiday_id == 1 ? $start_time : 0;
                $work_time_end = $work_holiday_id == 1 ? $end_time : 0;
                $actual_work_time = $work_holiday_id == 1 ? $actual_time : 0;

                //固定でクリアのデータ
                $approval_state_id = 1;
                $unemployed_id = 0;
                $statutory_working_time = 0;
                $non_statutory_working_time = 0;
                $midnight_time = 0;
                $break_time = 0;
                $midnight_break_time = 0;
                $holiday_work_break_time = 0;
                $holiday_midnight_work_break_time = 0;
                $deduction_time = 0;
                $unemployed_time = 0;
                $holiday_work_time = 0;
                $holiday_midnight_work_time = 0;
                $absent_time = 0;
                $substitute_holiday_reason = "";
                $information = "";
                $remand_reason = "";
                $approval_request_date = 0;
                $input_employee_id = 0;
                $approval_employee_id = 0;

                //現在日時取得
                date_default_timezone_set('Asia/Tokyo');
                $updated_at = date('Y-m-d H:i:s');
                $updated_user = $login_employee_id;

                //Update
                $t002_attendance_information->updateAttendanceInformation(
                    $employee_id,
                    $approval_state_id,
                    $attendance_date,
                    $work_holiday_id,
                    $work_achievement_id,
                    $work_zone_id,
                    $unemployed_id,
                    $work_time_start,
                    $work_time_end,
                    $actual_work_time,
                    $statutory_working_time,
                    $non_statutory_working_time,
                    $midnight_time,
                    $break_time,
                    $midnight_break_time,
                    $holiday_work_break_time,
                    $holiday_midnight_work_break_time,
                    $deduction_time,
                    $unemployed_time,
                    $holiday_work_time,
                    $holiday_midnight_work_time,
                    $absent_time,
                    $substitute_holiday_reason,
                    $information,
                    $remand_reason,
                    $approval_request_date,
                    $input_employee_id,
                    $approval_employee_id,
                    $updated_user,
                    $updated_at
                );

                if ($info['work_achievement_id'] == 7) {
                    $substitute_holiday_date = $info['substitute_holiday_date'];
                    if ($substitute_holiday_date != 0) {
                        //振替休日データ取得
                        $substitute_calendar_setting = $m022_calendar_setting->getCalendarSettingByDate($calendar_id, $substitute_holiday_date);
                        $substitute_work_holiday_id = $substitute_calendar_setting->work_holiday_id;
                        $substitute_work_zone_id = $substitute_work_holiday_id == 1 ? $employee->work_zone_id : 0;
                        $substitute_work_time_start = $substitute_work_holiday_id == 1 ? $start_time : 0;
                        $substitute_work_time_end = $substitute_work_holiday_id == 1 ? $end_time : 0;
                        $substitute_actual_work_time = $substitute_work_holiday_id == 1 ? $actual_time : 0;
                        //振休Update
                        $t002_attendance_information->updateSubstituteHoliday($employee_id, $substitute_holiday_date, $substitute_work_zone_id, $substitute_work_time_start, $substitute_work_time_end, $substitute_actual_work_time);
                        //振替休日更新
                        $t004_substitute_information->updateSubsttuteHolidayDate($employee_id, $substitute_holiday_date);
                    } else {
                        //振替休日更新
                        $attendance_information_id = $info['attendance_information_id'];
                        $attendance_date = $info['attendance_date'];
                        $t004_substitute_information->updateHolidaySubstituteDate($employee_id, $attendance_information_id, $attendance_date);
                    }
                }
                //該当の時間外実績、不就業、休暇取得情報を削除する
                $t007_over_time_achievement->deleteData($employee_id, $attendance_date);
                $t008_unemployed_information->deleteData($employee_id, $attendance_date);
                $t010_acquired_holiday->deleteData($employee_id, $attendance_date);

                //T003更新
                $is_updated = $aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term);
                if (!$is_updated) {
                    Log::error(2, 2, "AggregateFunctions", $employee->employee_id);
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "集計に失敗しました。システム管理者へ確認を行ってください",
                        ],
                    ]);
                }

                Log::info(2, 2, "申請取り消し実施", $employee->employee_id);
                // 本人締め自動化処理呼び出し
                Log::info(2, 2, "本人締め自動化処理 申請取り消し ".$closeStateId, $employee->employee_id);
                $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, $closeStateId);
                $result = true;
                break;
            case 6:
                //承認解除
                //締め状態を初期状態に戻す
                $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, CLOSE_STATE_THEMSELVES);
                $t002_attendance_information->approveUnrecognized($info);
                Log::info(2, 2, "承認解除実施", $employee->employee_id);
                $result = true;
                break;
            case 7:
                // 申請・承認
                // 申請処理

                // 振休・不就業
                if($info['work_achievement_id'] === 9 || ($info['unemployed_id'] === 0 && $info['work_achievement_id'] === 0)){

                    //現在日時取得
                    date_default_timezone_set('Asia/Tokyo');
                    $updated_at = date('Y-m-d H:i:s');
                    $updated_user = $login_employee_id;
                    $information = "";
                    if(isset($info['information'])){
                        $information = $info['information'];
                    }

                    //実績情報を更新
                    $t002_attendance_information->updateApprovalStateIdAndInformation($info['attendance_information_id'],$updated_user,$updated_at,$information);

                    Log::info(2, 2, "申請実施 （申請・承認処理 振休・不就業時）", $employee->employee_id);

                    // 振休・不就業時はこのブロックで完結するため、ここで承認処理を実施する
                    // 承認処理
                    //承認状態へ変更
                    Log::info(2, 2, "承認実施 （申請・承認処理 振休・不就業時）", $employee->employee_id);
                    $t002_attendance_information->approve($info, $login_employee_id);

                    //管理者締め自動化処理呼び出し
                    $closeStateId = $params['closeStateId'];
                    Log::info(2, 2, "管理者締め自動化処理 申請・承認（振休・不就業時）", $employee->employee_id);
                    $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, $closeStateId);

                    //完了
                    $result = true;
                    break;
                }

                //振替休日の申請時のみ先にチェック
                if($info['work_achievement_id'] === 7)
                {
                    //カレンダ設定
                    $key_index = array_search($info['substitute_holiday_date'], array_column($employee->calendar->calendar_setting->toArray(), 'calendar_date'));
                    if($key_index)
                    {
                        //振替先が通常日かどうか
                        if($employee->calendar->calendar_setting[$key_index]['work_holiday_id'] != 1)
                        {
                            //振替NG
                            return response()->json([
                                'result' => false,
                                'values' => [
                                    'message' => "振替休日の対象に休日が指定されています。通常勤務日を指定してください。",
                                ],
                            ]);
                            return;
                        }
                    }
                    else
                    {
                        //カレンダー見つからない
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' =>  "指定した日付は振替休日として選択できません",
                            ],
                        ]);
                    }
                    
                    //振替休日指定なし
                    if($info['substitute_holiday_date'] == null || $info['substitute_holiday_date'] == 0)
                    {
                        //取り消しの場合、既に振替休日を取得済みの時、取り消しは不可
                        $sub_infor_by_id = $t004_substitute_information->getSubstituteHolidayByAttendanceInformationID($info['attendance_information_id']);
                        if($sub_infor_by_id != null && count($sub_infor_by_id) != 0)
                        {
                            if($sub_infor_by_id['acquired_substitue_holiday_date'] != 0)
                            {
                                //取得済みのためエラー
                                return response()->json([
                                    'result' => false,
                                    'values' => [
                                        'message' => "既に振替予定先に勤務情報が登録されています。振替休日情報を変更できません。",
                                    ],
                                ]);
                            }
                        }
                    }
                    //振替休日指定あり
                    else
                    {
                        //振替対象が初期状態以外の時は、指定不可
                        $target_date_info = $t002_attendance_information->getAttendanceInformationByDate($employee->employee_id, $info['substitute_holiday_date']);
                        if($target_date_info != null)
                        {
                            if($target_date_info->approval_state_id != 1)
                            {
                                //振替NG
                                return response()->json([
                                    'result' => false,
                                    'values' => [
                                        'message' => "振替休日の対象に既に申請もしくは承認が行われています。他の勤務日を指定してください。",
                                    ],
                                ]);
                            }
                        }
                        //振替休日予定日は 当月であれば指定可とする（前月以前は指定不可）
                        $attendanceDateSerialOfMonthFirstDay = $cf->dateToSerial($cf->serialToDateStr($info['attendance_date'], 'Y-m') . '-01');
                        if ($info['substitute_holiday_date'] < $attendanceDateSerialOfMonthFirstDay) {
                            //振替予定日NG
                            return response()->json([
                                'result' => false,
                                'values' => [
                                    'message' => "振替休日は当月以降を選択してください。他の勤務日を指定してください。",
                                ],
                            ]);
                        }
                        //一覧取得
                        $substitute_information = $t004_substitute_information->getSubstituteHolidayDate($employee->employee_id);
                        
                        if($substitute_information != null && count($substitute_information) != 0)
                        {
                            //既に他の振替日に設定されていないか
                            //振替対象、もしくは振替済みに一致しするものを探す
                            //振替対象日
                            foreach($substitute_information as $sub)
                            {
                                if($sub->substitute_holiday_date == $info['substitute_holiday_date'] || $sub->acquired_substitue_holiday_date == $info['substitute_holiday_date'])
                                {
                                    //見つかった
                                    //申請日当日（上書き）でない場合はNG
                                    if($sub->attendance_information_id != $info['attendance_information_id'])
                                    {
                                        //振替NG
                                        return response()->json([
                                            'result' => false,
                                            'values' => [
                                                'message' => "指定した振替休日予定日に他の振替休日予定もしくは実績が登録されています",
                                            ],
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
                //ここまでで振替対象チェック完了
                //不就業がある場合、条件取得
                for($i = 0; $i < count($info['unemployed_array_valid']); $i++)
                {
                    $info['unemployed_array_valid'][$i]['unemployed_info'] = m031_unemployed::find($info['unemployed_array_valid'][$i]['unemployed_id']);
                }
                //不就業がある場合、実働や休憩から引く
                if(0 < count($info['unemployed_array_valid']))
                {
                    if($info['exclude_actual_work_time'])
                    {
                        //実働もしくは休日実働から引く
                        $info['actual_work_time'] = $info['actual_work_time'] - $info['exclude_actual_work_time'] < 0 ? 0 : $info['actual_work_time'] - $info['exclude_actual_work_time'];
                        $info['holiday_work_time'] = $info['holiday_work_time'] - $info['exclude_actual_work_time'] < 0 ? 0 : $info['holiday_work_time'] - $info['exclude_actual_work_time'];
                    }
                    if($info['exclude_rest_time'])
                    {
                        //休憩もしくは休日休憩から引く
                        $info['break_time'] = $info['break_time'] - $info['exclude_rest_time'] < 0 ? 0 : $info['break_time'] - $info['exclude_rest_time'];
                        $info['holiday_work_break_time'] = $info['holiday_work_break_time'] - $info['exclude_rest_time'] < 0 ? 0 : $info['holiday_work_break_time'] - $info['exclude_rest_time'];
                    }
                    if($info['exclude_midnight_actual_work_time'])
                    {
                        //深夜実働もしくは休日深夜実働から引く
                        $info['midnight_time'] = $info['midnight_time'] - $info['exclude_midnight_actual_work_time'] < 0 ? 0 : $info['midnight_time'] - $info['exclude_midnight_actual_work_time'];
                        $info['holiday_midnight_work_time'] = $info['holiday_midnight_work_time'] - $info['exclude_midnight_actual_work_time'] < 0 ? 0 : $info['holiday_midnight_work_time'] - $info['exclude_midnight_actual_work_time'];
                    }
                    if($info['exclude_midnight_rest_time'])
                    {
                        //深夜休憩もしくは休日深夜休憩から引く
                        $info['midnight_break_time'] = $info['midnight_break_time'] - $info['exclude_midnight_rest_time'] < 0 ? 0 : $info['midnight_break_time'] - $info['exclude_midnight_rest_time'];
                        $info['holiday_midnight_work_break_time'] = $info['holiday_midnight_work_break_time'] - $info['exclude_midnight_rest_time'] < 0 ? 0 : $info['holiday_midnight_work_break_time'] - $info['exclude_midnight_rest_time'];
                    }
                }
                //時間外時間を実働や休憩へ足す
                if(0 < count($info['over_time_class_array_valid']))
                {
                    if($info['additional_actual_work_time'])
                    {
                        //実働もしくは休日実働へ足す
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_work_time'] += $info['additional_actual_work_time'];
                        }
                        else
                        {
                            $info['actual_work_time'] += $info['additional_actual_work_time'];
                        }
                    }
                    if($info['additional_break_time'])
                    {
                        //休憩もしくは休日休憩へ足す
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_work_break_time'] += $info['additional_break_time'];
                        }
                        else
                        {
                            $info['break_time'] += $info['additional_break_time'];
                        }
                    }
                    if($info['additional_midnight_time'])
                    {
                        //深夜実働もしくは休日深夜実働へ足す
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_midnight_work_time'] += $info['additional_midnight_time'];
                        }
                        else
                        {
                            $info['midnight_time'] += $info['additional_midnight_time'];
                        }
                    }
                    if($info['additional_midnight_break_time'])
                    {
                        //深夜休憩もしくは休日深夜休憩から引く
                        if($info['work_achievement']['work_achievement_display_class'] == 3)
                        {
                            $info['holiday_midnight_work_break_time'] += $info['additional_midnight_break_time'];
                        }
                        else
                        {
                            $info['midnight_break_time'] += $info['additional_midnight_break_time'];
                        }
                    }
                }
                //実働時間がマイナスになっている場合は休憩指定など入力がおかしいのでエラー
                if($info['actual_work_time'] < 0 || $info['holiday_work_time'] < 0 || $info['break_time'] < 0 || $info['holiday_work_break_time'] < 0 || 
                    $info['midnight_time'] < 0 || $info['holiday_midnight_work_time']< 0 || $info['midnight_break_time'] < 0 || $info['holiday_midnight_work_break_time'] < 0)
                {
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "時間外時間もしくは不就業時間の入力により、実働時間が計算できない項目があります",
                        ],
                    ]);
                }

                //法定内時間外の再計算
                $actual_and_midnight_work_time = $info['actual_work_time'] + $info['midnight_time'];
                if(isset($info['additional_overtime_in_unemployed']))
                {
                    $actual_and_midnight_work_time += $info['additional_overtime_in_unemployed'];
                }
                if($actual_and_midnight_work_time <= $info['employee']['overtime_base_time'])
                {
                    $info['statutory_working_time'] = 0;
                }
                else if($actual_and_midnight_work_time <= 8 * 60)
                {
                    $info['statutory_working_time'] = $actual_and_midnight_work_time - $info['employee']['overtime_base_time'];
                }
                else
                {
                    $info['statutory_working_time'] = 8 * 60 - $info['employee']['overtime_base_time'];
                }
                //法定外時間外の再計算
                $info['non_statutory_working_time'] = 8 * 60 < $actual_and_midnight_work_time ? $actual_and_midnight_work_time - 8 * 60 : 0;

                //以降は他の計算完了後に実施
                //実働時間を再計算
                if(isset($info['work_achievement']) && $info['work_achievement']['work_achievement_display_class'] == 3)
                {
                    //休日時間を実働時間に計上
                    $info['actual_work_time'] = $info['holiday_work_time'] + $info['holiday_midnight_work_time'];
                }
                else
                {
                    //深夜時間を算入
                    $info['actual_work_time'] = $info['actual_work_time'] + $info['midnight_time'];
                }

                //nullのままだとエラー出るので0を代入
                if($info['midnight_time'] == null)
                {
                    $info['midnight_time'] = 0;
                }
                
                //申請者情報と申請日をセット
                $info['approval_request_date'] = $cf->getTodaySerial();
                $info['input_employee_id'] = $login_employee_id;
                $info['approval_employee_id'] = 0; //承認者はリセット
                $info_substitute_holiday_reason = "";
                if($info['work_achievement_id'] == 7){
                    $info_substitute_holiday_reason = "休日勤務(" . $cf->serialToDate($info['attendance_date']) . ")分の振替休日取得";
                }

                if(isset($info['substitute_holiday_date'])){
                    if($info['substitute_holiday_date'] != 0){
                        //登録された振替休日取得
                        $t004_substitute_information_info = $t004_substitute_information->getSubstituteHolidayByAttendanceInformationID($info['attendance_information_id']);
                        if($t004_substitute_information_info != null){
                            //振休が登録されている場合取り消し
                            $t002_attendance_information->updateSubstituteHoliday($info['employee_id'], $t004_substitute_information_info->substitute_holiday_date, $info['work_zone_id'], $info['work_time_start'], $info['work_time_end'], $info['actual_work_time']);
                        }
                    }
                }

                //実績情報を更新
                $t002_attendance_information->applyAttendanceInformation($info, 2, $info_substitute_holiday_reason);
                //不就業情報更新
                $t008_unemployed_information->applyUnemployedInformation($info);
                //時間外情報更新
                $t007_over_time_achievement->applyOverTimeAchievementInformation($info);
                //振替休日更新
                $t004_substitute_information->applySubsttuteHolidayDate($info, $info_substitute_holiday_reason);
                //休暇取得情報登録
                $t010_acquired_holiday->applyAcauiredHoliday($info);
                //休日出勤者情報登録
                $t011_holiday_worker_information->applyHolidayWorkerInformation($info);

                //T003更新
                $is_updated = $aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term);
                if(!$is_updated){
                    Log::error(2, 2, "AggregateFunctions", $employee->employee_id);
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "集計に失敗しました。システム管理者へ確認を行ってください",
                        ],
                    ]);
                }
                Log::info(2, 2, "申請実施 （申請・承認処理）", $employee->employee_id);

                // 承認処理
                //承認状態へ変更
                Log::info(2, 2, "承認実施 （申請・承認処理）", $employee->employee_id);
                $t002_attendance_information->approve($info, $login_employee_id);

                //管理者締め自動化処理呼び出し
                Log::info(2, 2, "管理者締め自動化処理 申請・承認", $employee->employee_id);
                $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, 2);
                
                $result = true;

                break;
            case 8:
                // 申請・承認取り消し
                // 差戻処理実施
                // //締め状態を初期状態に戻す
                Log::info(2, 2, "差戻実施 （申請・承認取り消し処理）", $employee->employee_id);
                $t002_attendance_information->remand($info);

                // 申請取り消し処理実施
                //対象社員ID
                $employee_id = $employee->employee_id;
                if($info['work_achievement_id'] === 9){
                    //現在日時取得
                    date_default_timezone_set('Asia/Tokyo');
                    $updated_at = date('Y-m-d H:i:s');
                    $updated_user = $login_employee_id;

                    $t002_attendance_information->cancelApprovalStateIdAndInformation($info['attendance_information_id'],$updated_user,$updated_at);

                    Log::info(2, 2, "申請取り消し実施 （申請・承認取り消し処理 振休時）", $employee_id);

                    //管理者締め自動化処理呼び出し
                    $closeStateId = $params['closeStateId'];
                    Log::info(2, 2, "管理者締め自動化処理 申請・承認取り消し処理 振休時", $employee->employee_id);
                    $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, $closeStateId);

                    $result = true;
                    break;
                }

                //勤務帯から始業時間、終業時間、実働時間を算出
                $calendar_id = $employee->calendar_id; //ToDo個人カレンダー対応
                $work_zone = m023_work_zone::find($employee->work_zone_id);
                $actual_time = 0;
                $start_time = 0;
                $end_time = 0;
                if($work_zone != null)
                {
                    $work_zone_times_work = $model_m024_work_zone_time->getStartEndTime($employee->work_zone_id,1);
                    $work_zone_times_rests = $model_m024_work_zone_time->getStartEndList($employee->work_zone_id,2);
                    if($work_zone_times_work)
                    {
                        $start_time = $work_zone_times_work->start_time;
                        $end_time = $work_zone_times_work->end_time;
                        $actual_time += ($work_zone_times_work->end_time - $work_zone_times_work->start_time);
                    }
                    foreach($work_zone_times_rests as $work_zone_times_rest)
                    {
                        $actual_time -= ($work_zone_times_rest->end_time - $work_zone_times_rest->start_time);
                    }
                }
                
                //カレンダー取得
                $calendar_setting = $m022_calendar_setting->getCalendarSettingByDate($calendar_id, $info['attendance_date']);
                //日毎に違うデータ
                $attendance_date = $info['attendance_date'];
                $work_holiday_id = $calendar_setting->work_holiday_id;
                $work_achievement_id = $work_holiday_id == 1 ? 1 : 0;
                $work_zone_id = $work_holiday_id == 1 ? $employee->work_zone_id : 0;
                $work_time_start = $work_holiday_id == 1 ? $start_time : 0;
                $work_time_end = $work_holiday_id == 1 ? $end_time : 0;
                $actual_work_time = $work_holiday_id == 1 ? $actual_time: 0;

                //固定でクリアのデータ
                $approval_state_id = 1;
                $unemployed_id = 0;
                $statutory_working_time = 0;
                $non_statutory_working_time = 0;
                $midnight_time = 0;
                $break_time = 0;
                $midnight_break_time = 0;
                $holiday_work_break_time = 0;
                $holiday_midnight_work_break_time = 0;
                $deduction_time = 0;
                $unemployed_time = 0;
                $holiday_work_time = 0;
                $holiday_midnight_work_time = 0;
                $absent_time = 0;
                $substitute_holiday_reason = "";
                $information = "";
                $remand_reason = "";
                $approval_request_date = 0;
                $input_employee_id = 0;
                $approval_employee_id = 0;

                //現在日時取得
                date_default_timezone_set('Asia/Tokyo');
                $updated_at = date('Y-m-d H:i:s');
                $updated_user = $login_employee_id;

                //Update
                $t002_attendance_information->updateAttendanceInformation(
                    $employee_id,
                    $approval_state_id,
                    $attendance_date,
                    $work_holiday_id,
                    $work_achievement_id,
                    $work_zone_id,
                    $unemployed_id,
                    $work_time_start,
                    $work_time_end,
                    $actual_work_time,
                    $statutory_working_time,
                    $non_statutory_working_time,
                    $midnight_time,
                    $break_time,
                    $midnight_break_time,
                    $holiday_work_break_time,
                    $holiday_midnight_work_break_time,
                    $deduction_time,
                    $unemployed_time,
                    $holiday_work_time,
                    $holiday_midnight_work_time,
                    $absent_time,
                    $substitute_holiday_reason,
                    $information,
                    $remand_reason,
                    $approval_request_date,
                    $input_employee_id,
                    $approval_employee_id,
                    $updated_user,
                    $updated_at
                );

                if($info['work_achievement_id'] == 7){
                    $substitute_holiday_date = $info['substitute_holiday_date'];
                    if($substitute_holiday_date != 0){
                        //振替休日データ取得
                        $substitute_calendar_setting = $m022_calendar_setting->getCalendarSettingByDate($calendar_id, $substitute_holiday_date);
                        $substitute_work_holiday_id = $substitute_calendar_setting->work_holiday_id;
                        $substitute_work_zone_id = $substitute_work_holiday_id == 1 ? $employee->work_zone_id : 0;
                        $substitute_work_time_start = $substitute_work_holiday_id == 1 ? $start_time : 0;
                        $substitute_work_time_end = $substitute_work_holiday_id == 1 ? $end_time : 0;
                        $substitute_actual_work_time = $substitute_work_holiday_id == 1 ? $actual_time: 0;
                        //振休Update
                        $t002_attendance_information->updateSubstituteHoliday($employee_id,$substitute_holiday_date,$substitute_work_zone_id,$substitute_work_time_start,$substitute_work_time_end,$substitute_actual_work_time);
                        //振替休日更新
                        $t004_substitute_information->updateSubsttuteHolidayDate($employee_id, $substitute_holiday_date);
                    }
                    else
                    {
                        //振替休日更新
                        $attendance_information_id = $info['attendance_information_id'];
                        $attendance_date = $info['attendance_date'];
                        $t004_substitute_information->updateHolidaySubstituteDate($employee_id, $attendance_information_id ,$attendance_date);
                    }
                }
                //該当の時間外実績、不就業、休暇取得情報を削除する
                $t007_over_time_achievement->deleteData($employee_id, $attendance_date);
                $t008_unemployed_information->deleteData($employee_id, $attendance_date);
                $t010_acquired_holiday->deleteData($employee_id, $attendance_date);

                //T003更新
                $is_updated = $aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term);
                if(!$is_updated){
                    Log::error(2, 2, "AggregateFunctions", $employee->employee_id);
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => "集計に失敗しました。システム管理者へ確認を行ってください",
                        ],
                    ]);
                }

                Log::info(2, 2, "申請取り消し実施 （申請・承認取り消し処理）", $employee->employee_id);

                //管理者締め自動化処理呼び出し
                $closeStateId = $params['closeStateId'];
                Log::info(2, 2, "管理者締め自動化処理 申請・承認取り消し処理", $employee->employee_id);
                $closingFunc->autoCloseManager($login_employee_id, $info['employee_id'], $target_term, $closeStateId);
                
                $result = true;

                break;
            default:
                $result = false;
                $message = '登録に失敗しました。ページを再読み込みしてください';
                break;
        }

        return response()->json([
            'result' => $result,
            'values' => [
                'message' => $message,
            ]
        ]);
    }

    /**
     * 日毎の詳細情報を登録 一括申請・一括申請せずに保存
     */
    public function saveBunchDailyInformation(Request $request){

        //共通関数
        $cf = new CommonFunctions();
        $aggregate_func = new AggregateFunctions();
        $closingFunc = new ClosingFunctions();
        //使用するDBインスタンス作成
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $t004_substitute_information = new t004_substitute_information();
        $t008_unemployed_information = new t008_unemployed_information();
        $t007_over_time_achievement = new t007_over_time_achievement();
        $t010_acquired_holiday = new t010_acquired_holiday();
        $t011_holiday_worker_information= new t011_holiday_worker_information();
        $m022_calendar_setting = new m022_calendar_setting();
        $model_m024_work_zone_time = new m024_work_zone_time();

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

        if (!isset($params['type']) || !in_array($params['type'], [2, 5])) {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正な呼び出しが行われました。ページを読み込みなおしてください。",
                ],
            ]);
        }
        $info = $params['info'];
        $checked_list = $params['checked_list'];
        if($info == null || $checked_list == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        //infoをBASE64デコードしてJSONへ
        $info = json_decode(base64_decode($info), true);
        $message = '';
        //申請者社員ID
        $employee = $request->session()->get('employee');
        if($employee == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $login_employee_id = $employee->employee_id;

        if(!isset($info['employee_id']))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "登録処理に失敗しました。ページを再度読み込みなおすか、再ログインを行ってください。",
                ],
            ]);
        }
        //対象社員情報
        $employee = m007_employee::find($info['employee_id']);

        //締め区分を取得
        $close_date_id = m007_employee::find($employee->employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        
        //日付と締め区分から対象年月を取得
        $target_term = $cf->getTargetTerm($info['attendance_date'], $close_date);
        //締め日を取得
        $close_term = $cf->getCloseTerm($target_term, $close_date);
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];

        //ここまでで振替対象チェック完了（登録OK）
        //不就業がある場合、条件取得
        for($i = 0; $i < count($info['unemployed_array_valid']); $i++)
        {
            $info['unemployed_array_valid'][$i]['unemployed_info'] = m031_unemployed::find($info['unemployed_array_valid'][$i]['unemployed_id']);
        }
        //不就業がある場合、実働や休憩から引く
        if(0 < count($info['unemployed_array_valid']))
        {
            if($info['exclude_actual_work_time'])
            {
                //実働もしくは休日実働から引く
                $info['actual_work_time'] = $info['actual_work_time'] - $info['exclude_actual_work_time'] < 0 ? 0 : $info['actual_work_time'] - $info['exclude_actual_work_time'];
                $info['holiday_work_time'] = $info['holiday_work_time'] - $info['exclude_actual_work_time'] < 0 ? 0 : $info['holiday_work_time'] - $info['exclude_actual_work_time'];
            }
            if($info['exclude_rest_time'])
            {
                //休憩もしくは休日休憩から引く
                $info['break_time'] = $info['break_time'] - $info['exclude_rest_time'] < 0 ? 0 : $info['break_time'] - $info['exclude_rest_time'];
                $info['holiday_work_break_time'] = $info['holiday_work_break_time'] - $info['exclude_rest_time'] < 0 ? 0 : $info['holiday_work_break_time'] - $info['exclude_rest_time'];
            }
            if($info['exclude_midnight_actual_work_time'])
            {
                //深夜実働もしくは休日深夜実働から引く
                $info['midnight_time'] = $info['midnight_time'] - $info['exclude_midnight_actual_work_time'] < 0 ? 0 : $info['midnight_time'] - $info['exclude_midnight_actual_work_time'];
                $info['holiday_midnight_work_time'] = $info['holiday_midnight_work_time'] - $info['exclude_midnight_actual_work_time'] < 0 ? 0 : $info['holiday_midnight_work_time'] - $info['exclude_midnight_actual_work_time'];
            }
            if($info['exclude_midnight_rest_time'])
            {
                //深夜休憩もしくは休日深夜休憩から引く
                $info['midnight_break_time'] = $info['midnight_break_time'] - $info['exclude_midnight_rest_time'] < 0 ? 0 : $info['midnight_break_time'] - $info['exclude_midnight_rest_time'];
                $info['holiday_midnight_work_break_time'] = $info['holiday_midnight_work_break_time'] - $info['exclude_midnight_rest_time'] < 0 ? 0 : $info['holiday_midnight_work_break_time'] - $info['exclude_midnight_rest_time'];
            }
        }
        //時間外時間を実働や休憩へ足す
        if(0 < count($info['over_time_class_array_valid']))
        {
            if($info['additional_actual_work_time'])
            {
                //実働もしくは休日実働へ足す
                if($info['work_achievement']['work_achievement_display_class'] == 3)
                {
                    $info['holiday_work_time'] += $info['additional_actual_work_time'];
                }
                else
                {
                    $info['actual_work_time'] += $info['additional_actual_work_time'];
                }
            }
            if($info['additional_break_time'])
            {
                //休憩もしくは休日休憩へ足す
                if($info['work_achievement']['work_achievement_display_class'] == 3)
                {
                    $info['holiday_work_break_time'] += $info['additional_break_time'];
                }
                else
                {
                    $info['break_time'] += $info['additional_break_time'];
                }
            }
            if($info['additional_midnight_time'])
            {
                //深夜実働もしくは休日深夜実働へ足す
                if($info['work_achievement']['work_achievement_display_class'] == 3)
                {
                    $info['holiday_midnight_work_time'] += $info['additional_midnight_time'];
                }
                else
                {
                    $info['midnight_time'] += $info['additional_midnight_time'];
                }
            }
            if($info['additional_midnight_break_time'])
            {
                //深夜休憩もしくは休日深夜休憩から引く
                if($info['work_achievement']['work_achievement_display_class'] == 3)
                {
                    $info['holiday_midnight_work_break_time'] += $info['additional_midnight_break_time'];
                }
                else
                {
                    $info['midnight_break_time'] += $info['additional_midnight_break_time'];
                }
            }
        }
        //法定内時間外の再計算
        $actual_and_midnight_work_time = $info['actual_work_time'] + $info['midnight_time'];
        if(isset($info['additional_overtime_in_unemployed']))
        {
            $actual_and_midnight_work_time += $info['additional_overtime_in_unemployed'];
        }
        if($actual_and_midnight_work_time <= $info['employee']['overtime_base_time'])
        {
            $info['statutory_working_time'] = 0;
        }
        else if($actual_and_midnight_work_time <= 8 * 60)
        {
            $info['statutory_working_time'] = 8 * 60 - ($actual_and_midnight_work_time);
        }
        else
        {
            $info['statutory_working_time'] = 8 * 60 - $info['employee']['overtime_base_time'];
        }
        //法定外時間外の再計算
        $info['non_statutory_working_time'] = 8 * 60 < $actual_and_midnight_work_time ? $actual_and_midnight_work_time - 8 * 60 : 0;

        //以降は他の計算完了後に実施
        //実働時間を再計算
        if(isset($info['work_achievement']) && $info['work_achievement']['work_achievement_display_class'] == 3)
        {
            //休日時間を実働時間に計上
            $info['actual_work_time'] = $info['holiday_work_time'] + $info['holiday_midnight_work_time'];
        }
        else
        {
            //深夜時間を算入
            $info['actual_work_time'] = $info['actual_work_time'] + $info['midnight_time'];
        }

        //nullのままだとエラー出るので0を代入
        if($info['midnight_time'] == null)
        {
            $info['midnight_time'] = 0;
        }
        
        //申請者情報と申請日をセット
        $info['approval_request_date'] = $cf->getTodaySerial();
        $info['input_employee_id'] = $login_employee_id;
        $info['approval_employee_id'] = 0; //承認者はリセット
        $info_substitute_holiday_reason = "";

        $first_work_holiday_flg = $info['work_holiday_id'] == 1 ? true : false;
        foreach($checked_list as $checked_id){

            $selected_attendance_information = t002_attendance_information::find($checked_id);
            if($selected_attendance_information == null){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                    ],
                ]);
            }
            $work_holiday_flg = $selected_attendance_information->work_holiday_id == 1 ? true : false;
            if($first_work_holiday_flg != $work_holiday_flg){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "出勤日と休日は別々に申請してください",
                    ],
                ]);
            }

            //振替休日収得
            $selected_substitute_information = $t004_substitute_information->getSubstituteHolidayByAttendanceInformationID($checked_id);
            if($selected_substitute_information != null){
                //振替休日取り消し
                $t004_substitute_information->updateSubsttuteHolidayDate($info['employee_id'], $selected_substitute_information->substitute_holiday_date);
                //振休が登録されている場合取り消し
                $t002_attendance_information->updateSubstituteHoliday($info['employee_id'], $selected_substitute_information->substitute_holiday_date, $info['work_zone_id'], $info['work_time_start'], $info['work_time_end'], $info['actual_work_time']);
            }
 
            //実績情報を更新
            $t002_attendance_information->applyAttendanceInformationWithId($info, $checked_id, $params['type']);
            //不就業情報更新
            $t008_unemployed_information->applyUnemployedInformationWithId($info, $checked_id,$selected_attendance_information->attendance_date);
            //時間外情報更新
            $t007_over_time_achievement->applyOverTimeAchievementInformationWithId($info, $checked_id,$selected_attendance_information->attendance_date);
            //休暇取得情報登録
            $t010_acquired_holiday->applyAcauiredHolidayWithId($info, $checked_id,$selected_attendance_information->attendance_date);
            //休日出勤者情報登録
            $t011_holiday_worker_information->applyHolidayWorkerInformationWithId($info, $checked_id,$selected_attendance_information->attendance_date);
        }

        //T003更新
        $is_updated = $aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term);
        if(!$is_updated){
            Log::info(2, 2, "エラー", $employee->employee_id);
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "集計失敗しました。",
                ],
            ]);
        }
        //本人締め状態の時に申請された場合に本人締め解除する
        $close_state_id = $t003_attendance_aggregate->getcloseStateId($employee->employee_id, $target_term);
        if($close_state_id->close_state_id == 2){
            $t003_attendance_aggregate->updateCloseStateId($employee->employee_id, $target_term, 1);
            //t002もその月のすべての日付を解除
            $t002_attendance_information->updateCloseStateId($employee->employee_id, $target_start_serial, $target_end_serial, 1);
            $t004_substitute_information->cancelCloseState($employee->employee_id, $target_start_serial, $target_end_serial);
        }

        if($params['type'] == 2) {
            Log::info(2, 2, "申請実施", $employee->employee_id);
        } else {
            Log::info(2, 2, "仮申請実施", $employee->employee_id);
        }

        // 本人締め自動化処理呼び出し
        $closingFunc->autoCloseThemSelves($login_employee_id, $info['employee_id'], $target_term, false);

        return response()->json([
            'result' => true,
            'values' => [
                'message' => $message,
            ]
        ]);

    }

    /**
     * 申請
     */
    public function informationAttendanceListApplication(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();
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
        $checked_list = $params['checked_list'];

        if($checked_list == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $t002_attendance_information = new t002_attendance_information();
        date_default_timezone_set('Asia/Tokyo');
        $updated_at = date('Y-m-d H:i:s');
        //申請者社員ID
        $employee = $request->session()->get('employee');
        if($employee == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $login_employee_id = $employee->employee_id;
        //対象社員情報
        $first_attendance_information = t002_attendance_information::find($checked_list[0]);
        $employee = m007_employee::find($first_attendance_information['employee_id']);

        foreach($checked_list as $checked_id){

            $selected_attendance_information = t002_attendance_information::find($checked_id);
            if($selected_attendance_information == null){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                    ],
                ]);
            }
            if($selected_attendance_information->approval_state_id != 5 
            && !($selected_attendance_information->approval_state_id == 1 && $selected_attendance_information->violation_warning_id == 1 && $selected_attendance_information->attendance_date < $cf->getTodaySerial())){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "「申請せずに保存」状態、「自動申請」状態の日のみ選択してください",
                    ],
                ]);
            }
        }
        foreach($checked_list as $checked_id){
            $t002_attendance_information->updateApprovalStateId($checked_id,$login_employee_id,$updated_at);
        }

        Log::info(2, 2, "申請実施（仮申請状態から申請）", $employee->employee_id);

        //本人締め自動化処理呼び出し
        $employee_id = $params['employee_id'];
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

        $target_term = $params['yearMonth'];
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
        
        $closingFunc->autoCloseThemSelves($login_employee_id, $employee_id, $target_term, false);
        Log::info(2, 2, "本人締め自動化処理（仮申請状態から申請）", $employee->employee_id);

        return response()->json([
            'result' => true,
            'values' => [
                'message' => '',
            ]
        ]);
    }

    /**
     * パスワードチェックと変更
     */
    public function checkUpdatePassword(Request $request)
    {

        //社員IDはSessionから取得
        $employee = $request->session()->get('employee');
        if($employee == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください",
                ],
            ]);
        }
        $employee_id = $employee->employee_id;

        $input_password = $request->input('input_password');
        $old_input_password = $request->input('old_input_password');
        $comform_password = $request->input('comform_password');

        $message = null;
        if(!$input_password || !$old_input_password || !$comform_password){
            $message = '現在のパスワード、新パスワード、確認用パスワードを入力してください';
        }
        if(strcmp($old_input_password, $input_password) === 0){
            $message = '旧パスワードと異なる新しいパスワードを再度入力してください';
        }
        else if(strcmp($input_password, $comform_password) !== 0){
            $message = '新パスワードと確認用パスワードが一致しません';
        }
        else if((strlen($input_password) > 12) || (strlen($input_password) < 4)){
            $message = 'パスワードは4～12文字で入力してください';
        }
        //E107-01-006
        else if(!preg_match('/^[A-Za-z0-9]*$/', $input_password)){
            $message = 'パスワードは半角英数のみ使用可能です';
        }
        if($message != null){
            //パスワード更新しない
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => $message,
                ]
            ]);
        }
        $model_m007_employee = new m007_employee();

        //Hash取得してチェック
        $employee_hash = $model_m007_employee->getHash($employee_id);

        if(Hash::check(strval($old_input_password), $employee_hash->stamping_password))
        {
            //パスワード更新
            $model_m007_employee->updatePasswordAndCopyToUser($employee_id,$input_password);

            //$model_User->updatePassword($employee_id,$employee_hash->stamping_password);

            return response()->json([
                'result' => true,
                'values' => [
                    'message' => "パスワードを変更しました",
                ]
            ]);
        }else{
            //パスワード更新しない
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "現在のパスワードが間違っています",
                ]
            ]);
        }
    }

    /**
     * @param $m007Employee 社員オブジェクト
     * @param $target_term  年月(例：202110)
     * @return array
     */
    public function getCloseStatus($m007Employee, $target_term)
    {
        //オフィス締めステータスと取得する、
        $is_office_closed = t014_office_closing_status::where('office_id', $m007Employee->office_id)
            ->where('close_date_id', $m007Employee->close_date_id)
            ->where('office_closing_year_month', $target_term)
            ->where('closing_status_class', 4)
            ->exists();

        //会社締めステータス取得する
        $is_company_closed = t015_company_closing_status::where('company_id', $m007Employee->company_id)
            ->where('close_date_id', $m007Employee->close_date_id)
            ->where('company_closing_year_month', $target_term)
            ->where('closing_status_class', 5)
            ->exists();

        return [
            'is_office_closed' => $is_office_closed,
            'is_company_closed' => $is_company_closed
        ];
    }
}
