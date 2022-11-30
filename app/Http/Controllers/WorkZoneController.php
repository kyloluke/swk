<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\m023_work_zone;
use App\Models\m024_work_zone_time;

class WorkZoneController extends Controller
{
    /**
     * 勤務帯更新
     */
    public function editWorkZone(Request $request)
    {
        //使用するDBインスタンス作成
        $model_m023_work_zone = new m023_work_zone();
        $model_m024_work_zone_time = new m024_work_zone_time();

        //勤務帯情報を取得
        $work_zone_id = $request->input('work_zone_id');
        $office_id = $request->input('office_id');
        $work_zone_code = $request->input('work_zone_code');
        $work_zone_name = $request->input('work_zone_name');
        $actual_work_time = $request->input('actual_work_time');
        $break_time = $request->input('break_time');
        $company_id = Auth::user()->company_id;
        $work_zone_aggrigation_class = $request->input('work_zone_aggrigation_class');

        //勤務帯時間情報を取得
        $actual_start_time = $request->input('actual_start_time');
        $actual_end_time = $request->input('actual_end_time');
        $break1_start_time = $request->input('break1_start_time');
        $break1_end_time = $request->input('break1_end_time');
        $break2_start_time = $request->input('break2_start_time');
        $break2_end_time = $request->input('break2_end_time');
        $break3_start_time = $request->input('break3_start_time');
        $break3_end_time = $request->input('break3_end_time');

        if($work_zone_code != null){
            if(20 < mb_strlen($work_zone_code)){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "コードは20文字以下としてください",
                    ]
                ]);
            }
        }

        if(mb_strlen($work_zone_name) < 2 || 30 < mb_strlen($work_zone_name)){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "名称は2文字以上30文字以下としてください",
                ]
            ]);
        }

        if(($break1_start_time == null || $break1_start_time == 0 || $break1_end_time == null || $break1_end_time == 0)
         && !(($break1_start_time == null || $break1_start_time == 0) && ($break1_end_time == null || $break1_end_time == 0))){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "所定休憩1の開始時刻、終了時刻はセットで入力してください",
                ]
            ]);
        }

        if(($break2_start_time == null || $break2_start_time == 0 || $break2_end_time == null || $break2_end_time == 0)
         && !(($break2_start_time == null || $break2_start_time == 0) && ($break2_end_time == null || $break2_end_time == 0))){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "所定休憩2の開始時刻、終了時刻はセットで入力してください",
                ]
            ]);
        }

        if(($break3_start_time == null || $break3_start_time == 0 || $break3_end_time == null || $break3_end_time == 0)
         && !(($break3_start_time == null || $break3_start_time == 0) && ($break3_end_time == null || $break3_end_time == 0))){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "所定休憩3の開始時刻、終了時刻はセットで入力してください",
                ]
            ]);
        }

        if(360 <= $actual_work_time && $actual_work_time < 480){
            if($break_time < 45){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "所定実働が6時間より多いため、所定時間内の所定休憩の合計を45分以上としてください",
                    ]
                ]);
            }
        }
        else if(480 <= $actual_work_time){
            if($break_time < 60){
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "所定実働が8時間より多いため、所定時間内の所定休憩の合計を60分以上としてください",
                    ]
                ]);
            }
        }
        //work_zone_time_idを特定
        $actual_work_zone_time_id = 0;
        //1～3を配列に加工
        $break_work_zone_time = [
            [
                'work_zone_time_id' => -1,
                'start_time' => $break1_start_time,
                'end_time' => $break1_end_time,
            ],
            [
                'work_zone_time_id' => -1,
                'start_time' => $break2_start_time,
                'end_time' => $break2_end_time,
            ],
            [
                'work_zone_time_id' => -1,
                'start_time' => $break3_start_time,
                'end_time' => $break3_end_time,
            ],
        ];
        if($work_zone_id !== null && $work_zone_id != 0)
        {
            //既存のWorkZoneIDあり
            $actual_work_time_list = $model_m024_work_zone_time->getStartEndList($work_zone_id, 1);
            if(count($actual_work_time_list) === 0)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "データベースエラーが発生しました。(work_zone is not found)",
                    ]
                ]);
            }
            $actual_work_zone_time_id = $actual_work_time_list[0]->work_zone_time_id;
            //break_timeのwork_zone_time_idを取得
            $break_time_list = $model_m024_work_zone_time->getStartEndList($work_zone_id, 2);
            for($i = 0; $i < count($break_work_zone_time); $i++)
            {
                if(isset($break_time_list[$i]))
                {
                    //更新か削除
                    $break_work_zone_time[$i]['work_zone_time_id'] = $break_time_list[$i]->work_zone_time_id;
                }
                else
                {
                    if($break_work_zone_time[$i]['start_time'] !== null && $break_work_zone_time[$i]['end_time'] !== null)
                    {
                        //新規
                        //新規の場合はwork_zone_time_idを0とする
                        $break_work_zone_time[$i]['work_zone_time_id'] = 0;
                    }
                    else
                    {
                        //値なし
                        //nothing to do
                    }
                }
            }
        }
        else
        {
            //work_zoneの新規作成
            for($i = 0; $i < count($break_work_zone_time); $i++)
            {
                if($break_work_zone_time[$i]['start_time'] !== null && $break_work_zone_time[$i]['end_time'] !== null)
                {
                    //新規
                    //新規の場合はwork_zone_time_idを0とする
                    $break_work_zone_time[$i]['work_zone_time_id'] = 0;
                }
                else
                {
                    //値なし
                    //nothing to do
                }
            }
        }

        //勤務帯を修正
        $work_zone_info = array();
        $work_zone_info['work_zone_id'] = $work_zone_id;
        $work_zone_info['office_id'] = $office_id;
        if ($work_zone_code == null){
            //nullの場合、空文字を登録
            $work_zone_info['work_zone_code'] = "";
        }
        else
        {
            $work_zone_info['work_zone_code'] = $work_zone_code;
        }
        $work_zone_info['work_zone_name'] = $work_zone_name;
        $work_zone_info['actual_work_time'] = $actual_work_time;
        $work_zone_info['break_time'] = $break_time;
        $work_zone_info['company_id'] = $company_id;
        $work_zone_info['work_zone_aggrigation_class'] = $work_zone_aggrigation_class;
        $result023 = $model_m023_work_zone->editWorkZoneInfo($work_zone_info);
        if($result023 != 0)
        {
            $work_zone_id = $result023;
        }

        //勤務帯時間情報を取得
        $work_zone_time_info = array();
        $work_zone_time_info['work_zone_id'] = $work_zone_id;
        $work_zone_time_info['actual_zone_time_id'] = $actual_work_zone_time_id;
        $work_zone_time_info['actual_start_time'] = $actual_start_time;
        $work_zone_time_info['actual_end_time'] = $actual_end_time;
        $work_zone_time_info['break_work_zone_time'] = $break_work_zone_time;
        //DBの勤務帯時間情報を修正
        $result024 = true;
        $result024 = $model_m024_work_zone_time->editWorkZoneTimeInfo($work_zone_time_info);


        if(!!$result024){
            return response()->json([
                'result' => true,
                'values' => [
                    'work_zone_time_info' => $work_zone_time_info,
                ]
            ]);
        }else{
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "データベースエラーが発生しました。",
                ]
            ]);
        }
    }
}
