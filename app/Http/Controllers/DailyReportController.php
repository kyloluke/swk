<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\t017_daily_report;

use App\Http\AppLibs\CommonFunctions;

class DailyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * 日報情報を取得
     */
    public function getDailyReport(Request $request)
    {
        //使用するDBインスタンス作成
        $model_t017_daily_report = new t017_daily_report();

        //日報情報を取得
        $employee_id = $request->input('employee_id');
        $target_date = $request->input('target_date');
        if(!$employee_id || !$target_date)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください",
                ]
            ]);
        }

        //勤務帯時間情報をDBへ登録
        $result = $model_t017_daily_report->t017GetDailyReport($employee_id, $target_date);

        if(!!$result){
            return response()->json([
                'result' => true,
                'values' => $result,
            ]);
        }else{
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "日報情報取得時にエラーが発生しました。",
                ]
            ]);
        }
    }
    /**
     * 日報情報を更新
     */
    public function updateDailyReport(Request $request)
    {
        //使用するDBインスタンス作成
        $model_t017_daily_report = new t017_daily_report();

        //日報情報を取得
        $employee_id = $request->employee_id;
        $target_date = $request->target_date;
        $daily_report_list = $request->daily_report_list;

        if(!$employee_id || !$target_date)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "リクエストが不正です。ページを読み込みなおしてください",
                ]
            ]);
        }
        if($daily_report_list != null && 0 < count($daily_report_list))
        {
            //work_noを時間順に整列する
            foreach((array)$daily_report_list as $key => $value)
            {
                $sort[$key] = $value['work_time_start_serial'];
            }
            array_multisort($sort, SORT_ASC, $daily_report_list);

            for($i = 0; $i < count($daily_report_list); $i++)
            {
                $daily_report_list[$i]['work_no'] = $i + 1;
            }
        }
        //Update実行
        $ret = $model_t017_daily_report->deleteAndUpdate($employee_id, $target_date, $daily_report_list);
        
        return response()->json([
            'result' => true,
            'values' => [
                'count' => $ret,
            ]
        ]);
    }
}