<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\m007_employee;
use App\Models\m021_calendar;
use App\Models\m022_calendar_setting;
use App\Http\AppLibs\CommonFunctions;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * カレンダ設定を取得
     */
    public function getCalendar(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $calendar_id = $request->input('calendar_id');
        $calendar_setting_year = $request->input('calendar_setting_year');
        if($calendar_id == null || $calendar_setting_year == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください"
                ]
            ]);
        }

        $model_m022_calendar_setting = new m022_calendar_setting();
        $calendar_info = $model_m022_calendar_setting->getCalendarSettingByYear($calendar_id, $calendar_setting_year);

        $calendar_setting_info = array();
        $calendar_setting_year_info = array();

        $old_month = 0;
        $year = 0;
        foreach($calendar_info as $info){

            $month = $cf->serialToMonthNumber($info->calendar_date);
            $day = $cf->serialToDayNumber($info->calendar_date);
            $week = intval($cf->serialToWeek($info->calendar_date));

            if($month != $old_month && $old_month != 0){
                $calendar_setting_year_info[] = array(
                    'calendar_setting_year_month' => strval($year) . "年" . strval($old_month) . "月",
                    'calendar_setting_info' => $calendar_setting_info,
                );
                $calendar_setting_info = [];
            }
            $old_month = $month;
            $year = $cf->serialToYearNumber($info->calendar_date);
                
            $calendar_setting_info[] = array(
                'calendar_setting_id' => $info->calendar_setting_id,
                'calendar_date' =>$info->calendar_date,
                'work_holiday_id' => $info->work_holiday_id,
                'day' => $day,
                'week' => $week,
            );
        }
        $calendar_setting_year_info[] = array(
            'calendar_setting_year_month' => strval($year) . "年" . strval($old_month) . "月",
            'calendar_setting_info' => $calendar_setting_info,
        );

        return response()->json([
            'result' => true,
            'values' => [
                'calendar_info' => $calendar_info,
                'calendar_setting_year_info' => $calendar_setting_year_info,
            ]
        ]);
    }

    /**
     * 祝日一覧取得
     */
    public function getHolidayList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $year = $request->input('calendar_setting_year');
        $month = $request->input('start_month');

        //コンテキストを設定
        $con = stream_context_create(array(
            'http' => array('ignore_errors' => true)));
        $holidays = [];

        for($i = 0; $i < 12; $i++)
        {
            $url = NATIONAL_HOLIDAYS_API . str_pad($year, 4, 0, STR_PAD_LEFT) . '/' . str_pad($month, 2, 0, STR_PAD_LEFT);
            
            if ($results = file_get_contents($url, true, $con))
            {
                //ステータスコードを取得する
                $code = substr($http_response_header[0], 9, 3);
                if($code == '500'){
                    return response()->json([
                        'result' => false,
                        'values' => [
                            'message' => '祝日データを取得できませんでした。管理者に連絡してください。',
                        ]
                    ]);
                }
                //ステータスコードによって処理を分岐する
                if($code != '404'){
                    // JSON形式で取得した情報を配列に格納
                    $holiday_info = json_decode($results);
        
                    foreach($holiday_info as $info) {
                        $data_info = [
                            'holiday_date' => $cf->dateToSerial($info->date),
                            'holiday_name' => $info->name,
                        ];
                        array_push($holidays, $data_info);
                    }
                }
            }else{
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => '祝日データを取得できませんでした。管理者に連絡してください。',
                    ]
                ]);
            }
            
            if($month == 12){
                $month = 1;
                $year++;
            }else{
                $month++;
            }
        }

        if(count($holidays) === 0){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '祝日データを取得できませんでした。※祝日データは、翌年分まで取得可能です',
                ]
            ]);
        }

        return response()->json([
            'result' => true,
            'values' => [
                'holidays' => $holidays,
            ]
        ]);
    }

    /**
     * カレンダ設定を編集
     */
    public function editCalendar(Request $request)
    {
        $calendar_info = $request->input('calendar_info');
        
        foreach($calendar_info as $info){

            $model_m022_calendar_setting = new m022_calendar_setting();
            $model_m022_calendar_setting->updateCalendarSetting($info['calendar_setting_id'],$info['work_holiday_id']);
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => '',
            ]
        ]);
    }


    /**
     * カレンダを新規作成
     */
    public function insertCalendar(Request $request)
    {
        $calendar_info = $request->input('calendar_info');
        $calendar_setting_year = $request->input('calendar_setting_year');
        $calendar_name = $request->input('calendar_name');
        $start_month = $request->input('start_month');
        $is_holiday_rest = $request->input('is_holiday_rest');
        $monday_work_holiday_id = $request->input('monday_work_holiday_id');
        $tuesday_work_holiday_id = $request->input('tuesday_work_holiday_id');
        $wednesday_work_holiday_id = $request->input('wednesday_work_holiday_id');
        $thursday_work_holiday_id = $request->input('thursday_work_holiday_id');
        $friday_work_holiday_id = $request->input('friday_work_holiday_id');
        $saturday_work_holiday_id = $request->input('saturday_work_holiday_id');
        $sunday_work_holiday_id = $request->input('sunday_work_holiday_id');
        $is_new_calendar = $request->input('is_new_calendar');
        $calendar_id = $request->input('calendar_id');

        //ログイン社員IDと会社IDを取得
        $company_id = Auth::user()->company_id;
        $employeeID = Auth::user()->employee_id;

        $model_m007_employee = new m007_employee();
        $user = $model_m007_employee->getEmployeeData($employeeID)->employee_code;

        $model_m021_calendar = new m021_calendar();

        if($is_new_calendar){
            $model_m021_calendar->insertCalendar($calendar_name, $start_month, $company_id, $is_holiday_rest, $monday_work_holiday_id, $tuesday_work_holiday_id, $wednesday_work_holiday_id, $thursday_work_holiday_id, $friday_work_holiday_id, $saturday_work_holiday_id, $sunday_work_holiday_id, $user);
            
            $calendar_id = $model_m021_calendar->lastCalendar()->calendar_id;
        }
        foreach($calendar_info as $info){
            $model_m022_calendar_setting = new m022_calendar_setting();
            $model_m022_calendar_setting->insertCalendarSetting($info['calendar_date'],$info['work_holiday_id'],$calendar_setting_year,$calendar_id, $user);
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => '',
                'calendar_id' => $calendar_id,
            ]
        ]);
    }

    /**
     * カレンダを新規作成
     */
    public function getCalendarList(Request $request)
    {

        $model_m021_calendar = new m021_calendar();
        $calendarList = $model_m021_calendar->getCalendarList();
        
        return response()->json([
            'result' => true,
            'values' => [
                'calendarList' => $calendarList,
            ]
        ]);
    }

    

}
