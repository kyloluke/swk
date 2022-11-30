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
use App\Models\t014_office_closing_status;
use App\Models\t015_company_closing_status;
use App\Models\t024_job_state;
use App\Models\m004_office;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m022_calendar_setting;
use App\Models\m023_work_zone;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\LogFunctions as Log;
use Exception;

class CreateAttendanceTables implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;
    protected $target_year_month;
    protected $company_id;
    protected $close_date_id;
    protected $job_state_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company_id, $target_year_month, $close_date_id, $job_state_id)
    {
        $this->company_id = $company_id;
        $this->target_year_month = $target_year_month;
        $this->close_date_id = $close_date_id;
        $this->job_state_id = $job_state_id;
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
        $t014_office_closing_status = new t014_office_closing_status();
        $t015_company_closing_status = new t015_company_closing_status();
        $t024_job_state = new t024_job_state();
        $m004_office = new m004_office();
        $m007_employee = new m007_employee();
        $m016_close_date = new m016_close_date();
        $m022_calendar_setting = new m022_calendar_setting();
        $cf = new CommonFunctions();

        $company_id = $this->company_id;
        $close_date_id = $this->close_date_id;
        $target_year_month = $this->target_year_month;
        $job_state_id = $this->job_state_id;
        $today_serial = $cf->getTodaySerial();

        //締め日指定にて全社員取得
        $employee_array = $m007_employee->getAllEmployeeBelongCompanyWithCloseDate($company_id, $close_date_id, $today_serial);
        $office_list = $m004_office->getOfficeList($company_id);

        //締め日と対象年月から期間を取得
        $close_date = $m016_close_date->getCloseDates($close_date_id)->close_date;
        if($close_date == null || $close_date == "")
        {
            $close_date = 0;
        }
        $close_term = $cf->getCloseTerm($target_year_month, $close_date);
        
        $ret_line = implode(",", $close_term);
        $ret_array = explode(",", $ret_line);//結合して再分割するとうまくいく。謎。

        $createStartDate = $ret_array[0];
        $createEndDate = $ret_array[1];
        Log::info(0, 999, "勤務表作成開始 >> " . $cf->serialToDate($createStartDate) . " to " . $cf->serialToDate($createEndDate), 0);
        //job_stateを開始に変更
        $t024_job_state->startJob($job_state_id);

        //キャンセルフラグ保持
        $state = 0;
        try
        {
            $ret_array = [];
            foreach($employee_array as $employee)
            {
                //t002作成に必要な情報取得
                $employee_id = $employee->employee_id;
                $violation_warning_id = 1;
                $approval_state_id = 1;
                $calendar_id = $employee->calendar_id; //ToDo個人カレンダー対応
                $work_zone = m023_work_zone::find($employee->work_zone_id);
                if($work_zone == null)
                {
                    $work_zone_times = [];
                    $star_time = 0;
                    $end_time = 0;
                }
                else
                {
                    $work_zone_times = $work_zone->work_zone_time;
                    $star_time = $work_zone_times->first()->start_time;
                    $end_time = $work_zone_times->first()->end_time;
                }
                $actual_time = 0;
                $substitute_holiday_reason = "";
                $information = "";
                $remand_reason = "";
                foreach($work_zone_times as $work_zone_time)
                {
                    if($work_zone_time->is_delete == 1)
                    {
                        continue;
                    }
                    if($work_zone_time->time_type_class == 1)
                    {
                        $actual_time += ($work_zone_time->end_time - $work_zone_time->start_time);
                    }
                    else
                    {
                        $actual_time -= ($work_zone_time->end_time - $work_zone_time->start_time);
                    }
                }
                for($target_date = $createStartDate; $target_date <= $createEndDate; $target_date++)
                {

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
                    $actual_work_time = $work_holiday_id == 1 ? $actual_time: 0;
                    
                    
                    if($attendance_date > $employee->retirement_company_date){
                        $work_achievement_id = 8;
                        $work_zone_id = 0;
                        $approval_state_id = 3;
                        $work_zone_time_start = 0;
                        $work_zone_time_end= 0;
                        $work_time_start = 0;
                        $work_time_end = 0;
                        $actual_work_time = 0;
                        $violation_warning_id = 1;
                    }
                    
                    //キャンセルフラグを確認
                    $state = $t024_job_state->getJobState($job_state_id);
                    if($state == 3)
                    {
                        break;
                    }
                    //Upsert
                    $t002_attendance_information->createAttendanceInformation(
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
                    );
                }
                //キャンセルフラグを確認
                if($state != 3)
                {
                    $state = $t024_job_state->getJobState($job_state_id);
                }
                if($state == 3)
                {
                    break;
                }
                //月の勤務表作成
                $t003_attendance_aggregate->createAttendanceAggregate($employee_id, $target_year_month);
                $ret_array[] = ['employee_id' => $employee_id, 'yearMonth' => $target_year_month];
            }
            //締め状態作成
            foreach($office_list as $office)
            {
                $t014_office_closing_status->createOfficeClosingStatus($office->office_id, $target_year_month, $close_date_id);
            }
            $t015_company_closing_status->createCompanyClosingStatus($company_id, $target_year_month, $close_date_id);
        }
        catch(Exception $e)
        {
            //job_stateを異常終了に変更
            $t024_job_state->errorJob($job_state_id);
        }
        Log::info(0, 999, "勤務表作成終了 >> " . $cf->serialToDate($createStartDate) . " to " . $cf->serialToDate($createEndDate), 0);
        //job_stateを終了に変更
        if($state != 3)
        {
            $t024_job_state->finishJob($job_state_id);
        }
    }
}
