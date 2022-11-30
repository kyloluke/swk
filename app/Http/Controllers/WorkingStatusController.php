<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t005_set_approval_target;
use App\Http\AppLibs\CommonFunctions;
use Illuminate\Support\Facades\DB;

class WorkingStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 対象の社員、月の勤怠情報、振替情報を取得
     */
    public function getWorkingStatusMonthly(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //ログイン中の社員情報を取得
        $employee_id = $request->session()->get('employee')->employee_id;

        //締め区分を取得
        $close_date_id = m007_employee::find($employee_id)->close_date_id;
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
        $target_start_serial = $close_term['start_sereial'];
        $target_end_serial = $close_term['end_sereial'];


        //勤怠データ取得
        $model_t002_attendance_information = new t002_attendance_information();
        $attendance_information = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $target_start_serial, $target_end_serial);
        //検証
        if(!$attendance_information)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }
        //返却用配列
        $attendance_information_array = array();
        foreach($attendance_information as $info)
        {
            $attendance_information_array[] = array(
                'approval_state_id' => $info->approval_state_id,
                'violation_warning_id' => $info->violation_warning_id,
            );
            continue;
        }

        //勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $attendance_aggregate_info = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);
        //検証
        if(!$attendance_aggregate_info)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //振替データ取得
        $model_t004_substitute_information = new t004_substitute_information();
        $substitute_info = $model_t004_substitute_information->getSubstituteHolidayDateWithinTerm($employee_id, $target_start_serial, $target_end_serial);
        //検証
        if(!$substitute_info)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                ]
            ]);
        }

        //承認対象者取得
        $t005_set_approval_target = new t005_set_approval_target();
        $target_person_info = $t005_set_approval_target->getTargetID($employee_id, $target_term);

        $model_m007_employee = new m007_employee();
        $target_array = [];
        foreach($target_person_info as $info)
        {

            $target_employee_data = $model_m007_employee->getEmployeeData($info->approved_person_id);

            // 社員コード
            if($target_employee_data != null){
                $target_employee_code = $target_employee_data->employee_code;
            }else{
                $target_employee_code = "";
            }

            $target_array[] = array(
                'employee_id' => $info->approved_person_id,
                'employee_code' => $target_employee_code,
            );
        }

        // ソートキー
        $target_array_employee_code = array_column($target_array, 'employee_code');
        array_multisort($target_array_employee_code, SORT_ASC, $target_array);

        $approved_array = array();
        foreach($target_array as $info){
            $target_person_id = $info['employee_id']; //被承認者
            //被承認者勤怠データ取得
            $model_t002_attendance_information = new t002_attendance_information();
            $approved_attendance_information = $model_t002_attendance_information->getAttendanceInformationWithinTerm($target_person_id, $target_start_serial, $target_end_serial);
            //検証
            if(!$approved_attendance_information)
            {
                //検証エラー
                return response()->json([
                    'result' => false,
                    'values' => [
                    ]
                ]);
            }

            //返却用配列
            $approved_attendance_information_array = array();
            foreach($approved_attendance_information as $information)
            {
                $approved_attendance_information_array[] = array(
                    'approval_state_id' => $information->approval_state_id,
                );
                continue;
            }
            $approved_array[] = array(
                'approved_attendance_information_array' => $approved_attendance_information_array,
            );
            continue;
        }

        //返却用配列
        $substitute_info_array = array();
        foreach($substitute_info as $info)
        {
            $substitute_info_array[] = array(
                'substitute_holiday_date' => $info->substitute_holiday_date,
                'acquired_substitue_holiday_date' => $info->acquired_substitue_holiday_date,
            );
            continue;
        }
        
        $WorkingStatusMonthlyInfo = [
            'attendance_aggregate_info' => $attendance_aggregate_info,
            'attendance_information' => $attendance_information_array,
            'substitute_info' => $substitute_info_array,
            'close_date_id' => $close_date_id,
            'approved_array' => $approved_array,
        ];
        return response()->json([
            'result' => true,
            'values' => $WorkingStatusMonthlyInfo,
        ]);
    }


}
