<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t009_holiday_management;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m033_grant_paid_leave_pattern;
use App\Models\m043_holiday;

use App\Http\AppLibs\CommonFunctions;
use Illuminate\Support\Facades\DB;

use App\Http\AppLibs\LogFunctions as Log;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class AbsentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * 個人の休暇情報の一覧を取得
     */
    public function getAbsentInfo(Request $request)
    {

        $employeeID = $request->input('employeeID');
        $targetdate = $request->input('targetdate');

        // 休暇情報の一覧取得処理を呼び出す
        $absentInfoData = $this->getAbsentInfoData($employeeID, $targetdate);

        return response()->json([
            'result' => true,
            'values' => $absentInfoData,
        ]);

    }


    /**
     * 休暇情報更新
     */
    public function updateHolidayManagement(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $holiday_management_id = $request->input('holiday_management_id');
        $holiday_id = $request->input('holiday_id');
        $grant_holiday_days = $request->input('grant_holiday_days');
        $acquired_holiday_days = $request->input('acquired_holiday_days');
        $employee_id = $request->input('employee_id');

        $model_t009_holiday_management = new t009_holiday_management();

        $old_remaining_holiday_days = t009_holiday_management::find($holiday_management_id)->remaining_holiday_days;

        $grant_date = t009_holiday_management::find($holiday_management_id)->grant_date;

        $t009_flag = $model_t009_holiday_management->updateHoliday($holiday_management_id,$grant_holiday_days,$acquired_holiday_days);
        
        $remaining_holiday_days = $grant_holiday_days - $acquired_holiday_days - $old_remaining_holiday_days;
        
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();

        $close_date_id = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        $year_month = $cf->getTargetTerm($grant_date, $close_date);

        if($holiday_id == 1){
            $model_t003_attendance_aggregate->updateRemainingPaidLeaveDays($employee_id,$year_month,$remaining_holiday_days);
        }
        if($holiday_id == 2){
            $model_t003_attendance_aggregate->updateUnusedAccumulatedPaidLeaveDays($employee_id,$year_month,$remaining_holiday_days);
        }

        if($t009_flag == 1 && $t009_flag != false){
            return response()->json([
                'result' => true,
                'values' => [
                    'holiday_management_id' => $holiday_management_id,
                    'grant_holiday_days' => $grant_holiday_days,
                    'acquired_holiday_days' => $acquired_holiday_days,
                ],
            ]);
        }else{
            return response()->json([
                'result' => false,
            ]);
        }
    }

    /**
     * 休暇情報新規
     */
    public function insertHolidayManagement(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $holiday_id = $request->input('holiday_id');
        $employee_id = $request->input('employee_id');
        $grant_date = $request->input('grant_date');
        $grant_holiday_days = $request->input('grant_holiday_days');
        $valid_date_end = $request->input('valid_date_end');

        //ログインされた社員コード
        $employee_code = $request->session()->get('employee')->employee_code;

        $date = $cf->serialToDate($valid_date_end);


        if(!$cf->checkDate($cf->serialToDate($grant_date)) || !$cf->checkDate($cf->serialToDate($valid_date_end))){
            return response()->json([
                'result' => false,
            ]);
        }

        $model_t009_holiday_management = new t009_holiday_management();

        $detail_no = $model_t009_holiday_management->countHoliday() + 1;

        $flag = $model_t009_holiday_management->insertHoliday($holiday_id,$employee_id,$grant_date,$grant_holiday_days,$valid_date_end,$detail_no,$date,$employee_code);

        if($holiday_id == 3){
            $close_date_id = m007_employee::find($employee_id)->close_date_id;
            $model_m016_close_date = new m016_close_date();
            $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
            $close_date = $close_date_info->close_date;

            $year_month = $cf->getTargetTerm($grant_date, $close_date);
            $model_t003_attendance_aggregate = new t003_attendance_aggregate();
            $flag = $model_t003_attendance_aggregate->updateRemainingPaidLeaveDays($employee_id,$year_month,$grant_holiday_days);
        }
        if($flag == 1){
            return response()->json([
                'result' => true,
                'values' => [
                    'holiday_management_id' => $detail_no,
                ],
            ]);
        }else{
            return response()->json([
                'result' => false,
            ]);
        }
    }

    /**
     * 休暇情報リスト取得
     */
    public function getHolidayList(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        //対象の社員情報を取得
        $holidayList = $request->session()->get('employee')['master_data']['holiday'];

        if($holidayList != null){
            return response()->json([
                'result' => true,
                'holidayList' => $holidayList,
            ]);
        }else{
            return response()->json([
                'result' => false,
            ]);
        }
    }

    /**
    * 事業所所属対象者（複数人）の休暇情報の一覧を取得
    */
    public function getAbsentInfoList(Request $request){

        $cf = new CommonFunctions();
        $model_t002_attendance_information = new t002_attendance_information();
        $employeeInfo = $request->input('employeeInfo');
        $targetdate = $request->input('targetdate');

        if(!$employeeInfo)
        {
            //検証エラー
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください"
                ]
            ]);
        }
        
        // 休暇情報の一覧取得処理を呼び出し、結果を配列に格納する
        $absent_info_array = [];
        for($i=0 ;$i < count($employeeInfo); $i++){

            //従業員コード
            $employee_code = m007_employee::find($employeeInfo[$i])->employee_code;

            //締め区分を取得
            $close_date_id = m007_employee::find($employeeInfo[$i])->close_date_id;
            $model_m016_close_date = new m016_close_date();
            $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
            $close_date = $close_date_info->close_date;

            //締め日を取得
            $close_term = $cf->getCloseTerm($targetdate, $close_date);
            $target_start_serial = $close_term['start_sereial'];
            $target_end_serial = $close_term['end_sereial'];

            $holiday_count = 0;
            $holiday_halfday_count = 0;
            $holiday_halfday_behind_time_count = 0;
            $holiday_halfday_leave_early_count = 0;
            $accumulated_holiday_count = 0;
            $accumulated_holiday_halfday_count = 0;
            //有休
            $holiday_count = count($model_t002_attendance_information->getAttendanceInformationWithinTermUnemployee($employeeInfo[$i], $target_start_serial, $target_end_serial, 1));
            //半休
            $holiday_halfday_count = count($model_t002_attendance_information->getAttendanceInformationWithinTermUnemployee($employeeInfo[$i], $target_start_serial, $target_end_serial, 2));
            $holiday_halfday_behind_time_count = count($model_t002_attendance_information->getAttendanceInformationWithinTermUnemployee($employeeInfo[$i], $target_start_serial, $target_end_serial, 3));
            $holiday_halfday_leave_early_count = count($model_t002_attendance_information->getAttendanceInformationWithinTermUnemployee($employeeInfo[$i], $target_start_serial, $target_end_serial, 5));
            //保存休暇
            $accumulated_holiday_count = count($model_t002_attendance_information->getAttendanceInformationWithinTermUnemployee($employeeInfo[$i], $target_start_serial, $target_end_serial, 10));
            //保存休暇(半休)
            $accumulated_holiday_halfday_count = count($model_t002_attendance_information->getAttendanceInformationWithinTermUnemployee($employeeInfo[$i], $target_start_serial, $target_end_serial, 11));
            //休暇情報の一覧を取得＋複数人処理で必要な情報を付与する
            $absent_info_array[] = array_merge($this->getAbsentInfoData($employeeInfo[$i], $target_end_serial),
                                            array(
                                                'employee_id' => $employeeInfo[$i],
                                                'employee_code' => $employee_code,
                                                'holiday_count' => $holiday_count,
                                                'holiday_half_count' => $holiday_halfday_count + $holiday_halfday_behind_time_count + $holiday_halfday_leave_early_count,
                                                'accumulated_holiday_count' => $accumulated_holiday_count,
                                                'accumulated_holiday_half_count' => $accumulated_holiday_halfday_count,
                                            ));
        }

        return response()->json([
            'result' => true,
            'values' => $absent_info_array,
        ]);
    }

    /**
     * 休暇情報の一覧取得(個人、複数の休暇情報一覧取得の共通部分を関数化）
     */
    private function getAbsentInfoData($employeeID, $targetdate){

        //共通関数
        $cf = new CommonFunctions();

        $model_t009_holiday_management = new t009_holiday_management();
        $model_m043_holiday = new m043_holiday();

        //付与起算入社日
        $grant_starting_date = m007_employee::find($employeeID)->grant_starting_date;
        //週所定日数
        $week_scheduled_working_days = m007_employee::find($employeeID)->week_scheduled_working_days;
        //初年度有給付与年月日
        $first_paid_leave_date = m007_employee::find($employeeID)->first_paid_leave_date;

        $today_serial = $cf->getTodaySerial();
        $first_paid_leave_month_day_number = $cf->serialToMonthDayNumber($first_paid_leave_date);
        $today_month_day_number = $cf->serialToMonthDayNumber($today_serial);

        $paid_leave_date_start = 0;
        if($first_paid_leave_month_day_number > $today_month_day_number){
            $paid_leave_date_start = $cf->serialToYearNumber($today_serial) * 10000 + $first_paid_leave_month_day_number;
            
        }else{
            $paid_leave_date_start = ($cf->serialToYearNumber($today_serial) + 1) * 10000 + $first_paid_leave_month_day_number;
            
        }

        $paid_leave_date_start_serial = $cf->numberToDateSerial($paid_leave_date_start);

        //勤続月数
        $grant_starting_year = $cf->serialToYearNumber($grant_starting_date);
        $grant_starting_month = $cf->serialToMonthNumber($grant_starting_date);
        $paid_leave_year_start = floor($paid_leave_date_start/10000);
        $paid_leave_month_start = floor($paid_leave_date_start/100) - $paid_leave_year_start * 100;

        $all_year_work_month = $paid_leave_month_start - $grant_starting_month + ($paid_leave_year_start - $grant_starting_year) * 12;
        
        $model_m033_grant_paid_leave_pattern = new m033_grant_paid_leave_pattern();
        $grant_paid_leave_pattern = $model_m033_grant_paid_leave_pattern->getGrantPaidLeavePattern($week_scheduled_working_days,$all_year_work_month);
        $grant_paid_leave_days = 0;
        //ー年間有給取得義務日数
        if($grant_paid_leave_pattern != null){
            $grant_paid_leave_days = $grant_paid_leave_pattern->grant_paid_leave_days;
        }

        $reserve_absent_info_with_id = $model_t009_holiday_management->getReserveHolidays($employeeID);
        $reserve_absent_info = [];

        foreach($reserve_absent_info_with_id as $reserve){
            
            $holiday_name = $model_m043_holiday->getHolidayNameById($reserve->holiday_id);
            
            $reserve_absent_info[] = [
                'holiday_management_id' => $reserve->holiday_management_id,
                'holiday_id' => $reserve->holiday_id,
                'holiday_name' => $holiday_name->holiday_name,
                'grant_date' => $reserve->grant_date,
                'grant_holiday_days' => $reserve->grant_holiday_days,
                'acquired_holiday_days' => $reserve->acquired_holiday_days,
                'remaining_holiday_days' => $reserve->remaining_holiday_days,
                'valid_date_end' => $reserve->valid_date_end,
            ];     
        }

        $today_acquired_absent_info = array();

        //有休データ一覧取得
        $today_acquired_absent_info = $model_t009_holiday_management->getThisHoliday($employeeID,$targetdate,1);
        $next_grant_holiday_days = '';
        if($today_acquired_absent_info){
            $next_grant_holiday_days = $model_t009_holiday_management->getGrantHolidayDays($employeeID, $today_acquired_absent_info->grant_date,1);
        }
        //有休データ一覧取得
        $acquired_absent_info = $model_t009_holiday_management->getHolidays($employeeID,1);
        //有効期間内休暇情報全取得、入社時有休（holiday_id：3）も加算する
        $target_acquired_holidays = $model_t009_holiday_management->getTargetHolidays($employeeID,$targetdate,[1,3]);
        //保存休データ一覧取得
        $accumulated_absent_info = $model_t009_holiday_management->getHolidays($employeeID,2);
        $target_accumulated_holidays = $model_t009_holiday_management->getTargetHolidays($employeeID,$targetdate,[2]);

        //返却
        $absent_info = [
            'today_acquired_absent_info' => $today_acquired_absent_info,
            'next_grant_holiday_days' => $next_grant_holiday_days,
            'acquired_absent_info' => $acquired_absent_info,
            'accumulated_absent_info' => $accumulated_absent_info,
            'reserve_absent_info' => $reserve_absent_info,
            'target_acquired_holidays' => $target_acquired_holidays,
            'target_accumulated_holidays' => $target_accumulated_holidays,
            'grant_starting_date' => $grant_starting_date,
            'paid_leave_date_start_serial' => $paid_leave_date_start_serial,
            'grant_paid_leave_days' => $grant_paid_leave_days,
        ];
        return $absent_info;

    }
}
