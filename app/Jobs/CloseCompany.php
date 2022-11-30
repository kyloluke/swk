<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t009_holiday_management;
use App\Models\t014_office_closing_status;
use App\Models\t015_company_closing_status;
use App\Models\m004_office;
use App\Models\m007_employee;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\LogFunctions as Log;
use Illuminate\Support\Facades\DB;
use App\Http\AppLibs\AggregateFunctions;
include_once(dirname(__FILE__).'/../Http/AppLibs/Const.php');

class CloseCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;
    protected $target_start_serial;
    protected $target_end_serial;
    protected $target_term;
    protected $company_id;
    protected $close_date_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($target_start_serial, $target_end_serial, $target_term, $company_id, $close_date_id)
    {
        $this->target_start_serial = $target_start_serial;
        $this->target_end_serial = $target_end_serial;
        $this->target_term = $target_term;
        $this->company_id = $company_id;
        $this->close_date_id = $close_date_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t002_attendance_information = new t002_attendance_information();
        $t003_attendance_aggregate = new t003_attendance_aggregate();
        $t004_substitute_information = new t004_substitute_information();
        $t009_holiday_management = new t009_holiday_management();
        $t014_office_closing_status = new t014_office_closing_status();
        $t015_company_closing_status = new t015_company_closing_status();
        $m004_office = new m004_office();
        $m007_employee = new m007_employee();

        $target_start_serial = $this->target_start_serial;
        $target_end_serial = $this->target_end_serial;
        $target_term = $this->target_term;
        $company_id = $this->company_id;
        $close_date_id = $this->close_date_id;

        //事業所締め状態更新
        $m004Office = $m004_office->getOfficeList($company_id);
        foreach($m004Office as $info)
        {
            $t014_office_closing_status->updateOfficeClosingStatus($info->office_id, $target_term, $close_date_id, CLOSE_STATE_COMPANY);
        }

        //勤怠情報、勤怠集計情報更新
        $employee_info = $m007_employee->getAllEmployeeBelongCompanyByCloseID($company_id, $close_date_id, $target_end_serial);
        foreach($employee_info as $info)
        {
            $t003_attendance_aggregate->updateCloseStateId($info->employee_id, $target_term, CLOSE_STATE_COMPANY);
            $t002_attendance_information->updateCloseStateId($info->employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_COMPANY);
            $substitute_holiday = $t002_attendance_information->getSubstituteHolidayWithinTerm($info->employee_id, $target_start_serial, $target_end_serial);
            $t004_substitute_information->updateCloseState($info->employee_id, $substitute_holiday);
            AggregateFunctions::aggregateAttendance($info, $target_start_serial, $target_end_serial, $target_term);
            //ここで取得する前にt003集計処理いれたい
            $attendance_aggregate = $t003_attendance_aggregate->getAttendanceAggregateWithinTerm($info->employee_id, $target_term);
            //検証
            if(!$attendance_aggregate)
            {
                continue;
            }

            //有休減算処理
            $acquired_paid_leave_days = $attendance_aggregate->acquired_paid_leave_days + $attendance_aggregate->acquired_paid_leave_half_days/2;
            $result = $t009_holiday_management->updateRemainingHoliday($info->employee_id, $target_start_serial, $target_end_serial, $acquired_paid_leave_days, 1);

            switch($result){
                case 1:
                    Log::info(0, 3, "有休残数更新エラー（更新種別指定）", $info->employee_id);
                    break;
                case 2:
                    Log::info(0, 3, "有休残数更新エラー（残数不足）", $info->employee_id);
                    break;
                default:
                    break;
            }

            //保存休減算処理
            $accumulated_paid_leave_days = $attendance_aggregate->accumulated_paid_leave_days + $attendance_aggregate->accumulated_paid_leave_half_days/2;
            $result = $t009_holiday_management->updateRemainingHoliday($info->employee_id, $target_start_serial, $target_end_serial, $accumulated_paid_leave_days, 2);

            switch($result){
                case 1:
                    Log::info(0, 3, "保存休残数更新エラー（更新種別指定）", $info->employee_id);
                    break;
                case 2:
                    Log::info(0, 3, "保存休残数更新エラー（残数不足）", $info->employee_id);
                    break;
                default:
                    break;
            }
        }

        //全社締め状態更新
        $t015_company_closing_status->updateCompanyClosingStatus($company_id, $target_term, $close_date_id, CLOSE_STATE_COMPANY);
    }
}
