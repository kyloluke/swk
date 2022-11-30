<?php

namespace App\Console\Commands;

use App\Http\AppLibs\ClosingFunctions;
use Illuminate\Console\Command;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\LogFunctions as Log;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m024_work_zone_time;
use App\Models\m028_web_punch_clock_deviation_time;
use App\Models\m030_work_achievement;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;
include_once(dirname(__FILE__).'/../../Http/AppLibs/Const.php');
class CheckDissociation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:checkdissociation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check desociation by m028_web_punch_clock_deviation_time and web_punch_clock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info(0, 999, '乖離判定開始', 0);
        //共通関数
        $cf = new CommonFunctions();

        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $model_t002_attendance_information = new t002_attendance_information();
        $model_m007_employee = new m007_employee();
        $model_m024_work_zone_time = new m024_work_zone_time();
        $model_m028_web_punch_clock_deviation_time = new m028_web_punch_clock_deviation_time();

        //まずはWeb打刻を転記
        $transfer_web_punch_array = $model_t001_web_punch_clock->getUnTransferData();
        foreach($transfer_web_punch_array as $web_punch)
        {
            //転送処理が必要なのは1出勤、2退勤のみ
            if($web_punch->clocking_in_out_id == 1)
            {
                $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($web_punch->employee_id, $web_punch->punch_clock_date, 1, $web_punch->punch_clock_time, $web_punch->input_class);
            }
            else if($web_punch->clocking_in_out_id == 2)
            {
                //退勤は転送済みの手入力打刻が無い場合のみ転送
                if($model_t001_web_punch_clock->getInputDataWithinData($web_punch->employee_id, $web_punch->punch_clock_date, $web_punch->clocking_in_out_id) == null){
                    $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($web_punch->employee_id, $web_punch->punch_clock_date, 2, $web_punch->punch_clock_time, $web_punch->input_class);
                }
            }
            //転送フラグを「１：転送完了」に更新
            $model_t001_web_punch_clock->updateTransferClass($web_punch->web_punch_clock_id, 1);
        }

        //乖離判定開始日
        //期間指定を行う場合
        //$start_dateに開始日(シリアル値)
        //$end_dateに終了日(シリアル値)
        //を指定すると指定した期間で処理が行われる
        $start_date = $cf->getTodaySerial() - 1; //導入時など全期間で実行する場合、この値を0にする
        $end_date = $cf->getTodaySerial() - 1;

        //期間指定を行うと対象月が複数月に跨ることがあるため、月末締め、１５日締めそれぞれで先に算出しておく
        $targetTermArrayEndClosing = $this->getClosingDateArray($start_date,$end_date,1);
        $targetTermArray15thClosing = $this->getClosingDateArray($start_date,$end_date,2);
        $disposalDay = $cf->getTodaySerial();

        $all_emplopyee = $model_m007_employee->getAllEmployeeID();
        foreach($all_emplopyee as $employee)
        {
            //管理者締めチェック用トリガー用
            $managerCloseTrigger = $end_date - $start_date;
            //出勤許容する時間取得
            $start_allow_time = $model_m028_web_punch_clock_deviation_time->getAllowTime($employee->deviation_time_before_start_time_id);
            //退勤許容する時間取得
            $end_allow_time = $model_m028_web_punch_clock_deviation_time->getAllowTime($employee->deviation_time_after_end_time_id);
            //データ取得
            $attendance_information_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee->employee_id, $start_date ,$end_date);

            if(count($attendance_information_info) == 0)
            {
                continue;
            }

            foreach($attendance_information_info as $info)
            {
                //申請状態が初期状態の以外の場合は更新しない
                if($info->approval_state_id != 1)
                {
                    continue;
                }
                //通常出勤日の判定
                if($info->work_holiday_id == 1){
                    //想定時間帯を取得(勤務時間：１)
                    $start_end_time = $model_m024_work_zone_time->getStartEndTime($info->work_zone_id, 1);
                    //想定時間が取得できない場合は乖離判定不能のため、乖離無しとする
                    if($info->work_zone_id == 0 ||$start_end_time == null)
                    {
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date, 1);
                        continue;
                    }
                    //手入力打刻乖離
                    if($model_t001_web_punch_clock->getInputDataWithinData($employee->employee_id, $info->attendance_date, 1) != null || $model_t001_web_punch_clock->getInputDataWithinData($employee->employee_id, $info->attendance_date, 2) != null){
                        //乖離あり
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date,2);
                        $managerCloseTrigger++;
                        continue;
                    }
                    if($start_allow_time->allow_before_start_time >= $start_end_time->start_time - $info->web_punch_clock_time_start &&
                        $info->web_punch_clock_time_start - $start_end_time->start_time <= $start_allow_time->allow_after_end_time &&
                        $end_allow_time->allow_before_start_time >= $start_end_time->end_time-$info->web_punch_clock_time_end &&
                        $info->web_punch_clock_time_end - $start_end_time->end_time <= $end_allow_time->allow_after_end_time){
                        //乖離なし
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date, 1);
                    }else{
                        //乖離あり
                        $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date, 2);
                        $managerCloseTrigger++;
                    }
                //休日の判定
                }else if($info->work_holiday_id == 2 || $info->work_holiday_id == 3){
                    //実績情報を取得
                    $model_m030_work_achievement = new m030_work_achievement();
                    $m030_work_achievement_data = $model_m030_work_achievement->getWorkAchievementDisplayClassByID($info->work_achievement_id);
                    //休日出勤していない場合
                    if($m030_work_achievement_data == null || ($m030_work_achievement_data =! null && $m030_work_achievement_data->work_achievement_display_class != 3 && $m030_work_achievement_data->work_achievement_display_class != 4)){
                        //手入力打刻乖離
                        if($model_t001_web_punch_clock->getInputDataWithinData($employee->employee_id, $info->attendance_date, 1) != null || $model_t001_web_punch_clock->getInputDataWithinData($employee->employee_id, $info->attendance_date, 2) != null){
                            //乖離あり
                            $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date,2);
                            $managerCloseTrigger++;
                            continue;
                        }
                        //打刻がある場合は乖離あり
                        if($info->web_punch_clock_time_start != null || $info->web_punch_clock_time_end != null){
                            //乖離あり
                            $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date,2);
                            $managerCloseTrigger++;
                        }else{
                            //乖離なし
                            $model_t002_attendance_information->updateAttendanceInformationViolationWarningId($employee->employee_id, $info->attendance_date,1);
                        }
                    }
                }
            }

            //勤務管理対象外
            if($employee->work_management_target_class == 0){
                continue;
            }

            //締め区分を取得
            $close_date_id = m007_employee::find($employee->employee_id)->close_date_id;
            $model_m016_close_date = new m016_close_date();
            $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
            $close_date = $close_date_info->close_date;

            //月末締め
            if($close_date_id==1){
                $targetTermArray = $targetTermArrayEndClosing;
            }else{
                $targetTermArray = $targetTermArray15thClosing;
            }

            $closeFunc = new ClosingFunctions();

            foreach($targetTermArray as $target_term){
                // 対象月の締め日を取得
                $close_term = $cf->getCloseTerm($target_term, $close_date);
                $target_end_serial = $close_term['end_sereial'];
                $isClosedThemSelves = true;
                // 処理が流れた時点で、締め状態が本人締め以上(close_status_id>=2)の場合は、本人締め処理は実施しない
                if($closeFunc->checkCloseStatus($employee->employee_id, $target_term, 2)){
                    //乖離がない人は申請を行う必要がなく、申請ボタンを押すというトリガーがない、この処理が流れたタイミングで本人締めのチェックを行う
                    Log::info(0,999,'本人締めのチェック実施 '.$target_term,$employee->employee_id);
                    $isClosedThemSelves = $closeFunc->autoCloseThemSelves($employee->employee_id, $employee->employee_id, $target_term, false);
                }

                //処理日付が対象月の締め日以降かつ、指定期間内で乖離なく、本人締めが完了している状態
                //承認ボタンを押すというトリガーがないため、この処理が流れたタイミングで管理者締めのチェックを行う
                if($disposalDay >= $target_end_serial && $isClosedThemSelves && $managerCloseTrigger == ($end_date-$start_date) ){
                    // 処理が流れた時点で、締め状態が管理者締め以上(close_status_id>=3)の場合は、管理者締め処理は実施しない
                    if($closeFunc->checkCloseStatus($employee->employee_id, $target_term, 3)){
                        Log::info(0,999,'管理者締めのチェック実施 '.$target_term,$employee->employee_id);
                        $closeFunc->autoCloseManager($employee->employee_id,$employee->employee_id,$target_term,2);
                    }
                }
            }

        }
        Log::info(0, 999, '乖離判定終了', 0);
        return 0;
    }

    /**
     * 締め区分で指定した日付の締め対象月を取得する
     */
    private function getClosingDateArray($start_date, $end_date, $close_date_id){
        $cf = new CommonFunctions();
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
        $close_date = $close_date_info->close_date;
        $targetTermArray = array();
        for($i=$start_date; $i <= $end_date; $i++){
            if(!in_array($cf->getTargetTerm($i, $close_date),$targetTermArray)){
                array_push($targetTermArray,$cf->getTargetTerm($i, $close_date));
            }
        }

        return $targetTermArray;
    }
}
