<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Http\AppLibs\CommonFunctions;
use Illuminate\Support\Facades\DB;

class LaborSituationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 労働・休暇状態確認画面のデータ取得
     */
    public function getLaborSituationInfo(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

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

        //締め区分を取得
        $close_date = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date);
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

        //t004_振替データ取得
        $model_t004_substitute_information = new t004_substitute_information();
        $t004_substitute_info = $model_t004_substitute_information->getSubstituteHolidayDateWithinTerm($employee_id, $target_start_serial, $target_end_serial);

        //返却用データ集約
        $labor_situation_info = [
            't002_attendance_info' => $t002_attendance_info,
            't003_attendance_aggregate' => $t003_attendance_aggregate,
            't004_substitute_info' => $t004_substitute_info,
        ];

        return response()->json([
            'result' => true,
            'values' => $labor_situation_info,
        ]);
    }
}
