<?php

namespace App\Http\AppLibs;

use App\Models\m007_employee;
use App\Models\m030_work_achievement;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\m016_close_date;

use App\Http\AppLibs\CommonFunctions;

use App\Http\AppLibs\LogFunctions as Log;

class ClosingFunctions
{
    /**
     * 本人締め自動化処理
     * 締めだけを抜き出して共通化する
     */
    public function autoCloseThemSelves($login_employee_id,$employee_id, $target_term, $recheck){
        //共通関数
        $cf = new CommonFunctions();
        $isCloseThemSelves = false;
        $result = true;
        $message = '';

        //締め区分を取得
        $close_date_id = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
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
            Log::info(2, 2,"不正なリクエストが行われました。t002_attendance_info",$employee_id);
            $result = false;
        }

        //t003_勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);
        //検証
        if(!$t003_attendance_aggregate)
        {
            Log::info(2, 2,"不正なリクエストが行われました。t003_attendance_aggregate",$employee_id);
            $result = false;
        }

        $model_t004_substitute_information = new t004_substitute_information();
        $substitute_information_info = $model_t004_substitute_information->countUnused($employee_id);
        $attendance_information_info = $model_t002_attendance_information->getDataInmonth($employee_id, $target_start_serial, $target_end_serial);

        $workAchievementDisplayClass5Count = 0;
        foreach($attendance_information_info as $ai_info){
            $m030_work_achievement_info = m030_work_achievement::find($ai_info->work_achievement_id);
            if($m030_work_achievement_info == null){
                continue;
            }
            if($m030_work_achievement_info->work_achievement_display_class == 5){
                $workAchievementDisplayClass5Count += 1;
            }
        }

        //休振の日数<振休の日数の場合
        if($substitute_information_info < $workAchievementDisplayClass5Count){
            //検証エラー
            Log::info(2, 2,"振替休日が振替出勤日よりも多くなっています。振替休日数を振替出勤日数以下にしてください。",$employee_id);
            $result = false;
        }
        
        //t001_打刻データ取得
        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $punch_clock_data = $model_t001_web_punch_clock->getDataWithinTerm($employee_id, $target_start_serial, $target_end_serial);

        $today_serial = $cf->getTodaySerial();
        
        if($this->checkStateId($t002_attendance_info, $employee_id, $today_serial)){
            // 本人締め可能状態
            //締め状態を本人締めに更新
            $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_THEMSELVES);
            $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, CLOSE_STATE_THEMSELVES);
            $model_t003_attendance_aggregate->updateCloseEmployeeId($employee_id, $login_employee_id, $target_term);
            $isCloseThemSelves = true;
            Log::info(2, 2,"本人締め完了",$employee_id);
        }
        else{
            // 本人締め不可状態
            // 本人締め解除
            $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, 1);
            $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, 1);
            $model_t004_substitute_information->cancelCloseState($employee_id, $target_start_serial, $target_end_serial);
            $isCloseThemSelves = false;
            Log::info(2, 2,"本人締め解除",$employee_id);
        }

        // 申請取り消しは更新後の状態を見る必要があるため、更新後の状態を見て再チェックを行う
        if($recheck){
            $t002_attendance_info = $model_t002_attendance_information->getAttendanceInformationWithinTerm($employee_id, $target_start_serial, $target_end_serial);

            if($this->checkStateId($t002_attendance_info, $employee_id, $today_serial )){
                // 本人締め可能状態
                //締め状態を本人締めに更新
                $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_THEMSELVES);
                $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, CLOSE_STATE_THEMSELVES);
                $model_t003_attendance_aggregate->updateCloseEmployeeId($employee_id, $login_employee_id, $target_term);
                $isCloseThemSelves = true;
                Log::info(2, 2,"再チェック後の本人締め完了",$employee_id);
            }
            else{
                // 本人締め不可状態
                // 本人締め解除
                $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, 1);
                $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, 1);
                $model_t004_substitute_information->cancelCloseState($employee_id, $target_start_serial, $target_end_serial);
                $isCloseThemSelves = false;
                Log::info(2, 2,"再チェック後の本人締め解除",$employee_id);
            }
 
        }

        return $isCloseThemSelves;
        
    }

    /**
     * 管理者締め自動化処理
     */
    public function autoCloseManager($login_employee_id, $employee_id, $target_term, $close_state_id){
        //共通関数
        $cf = new CommonFunctions();
        $manager_result = true;

        if($employee_id == 0){
            Log::info(2, 2,"employee_id 不備",$employee_id);
            $manager_result = false;
        }

        if(!preg_match('/^([0-9]{6})$/', $target_term))
        {
            Log::info(2, 2,"target_term 不備",$employee_id);
            $manager_result = false;
        }

        //検証
        if($close_state_id < 2)
        {
            Log::info(2, 2,"close_state_id 不備 ".$close_state_id,$employee_id);
            $manager_result = false;
        }

        //締め区分を取得
        $close_date_id = m007_employee::find($employee_id)->close_date_id;
        $model_m016_close_date = new m016_close_date();
        $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
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
            Log::info(2, 2,"t002_attendance_info 不備",$employee_id);
            $manager_result = false;
        }

        //t003_勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);
        //検証
        if(!$t003_attendance_aggregate)
        {
            Log::info(2, 2,"t003_attendance_aggregate 不備",$employee_id);
            $manager_result = false;
        }

        if($close_state_id == CLOSE_STATE_THEMSELVES){
            foreach($t002_attendance_info as $info)
            {
                //出休IDが「通常勤務」かつ承認状態IDが「承認済み」であること
                if($info->work_holiday_id == WORK_HOLIDAY_NORMAL){
                    if($info->approval_state_id != APPROVAL_STATE_DONE){
                        Log::info(2, 2,"APPROVAL_STATE_DONE ".$info->approval_state_id,$employee_id);
                        $manager_result = false;
                        break;
                    }
                }
                //出休IDが「通常勤務」以外の場合は「承認済み」or「初期状態」であること
                else{
                    if(!($info->approval_state_id == APPROVAL_STATE_INITIAL || $info->approval_state_id == APPROVAL_STATE_DONE)){
                        Log::info(2, 2,"APPROVAL_STATE_INITIAL or APPROVAL_STATE_DONE ".$info->approval_state_id,$employee_id);
                        $manager_result = false;
                        break;
                    }
                }
                //「本人締め」状態であること
                if($info->close_state_id != CLOSE_STATE_THEMSELVES){
                    Log::info(2, 2,"!CLOSE_STATE_THEMSELVES ".$info->close_state_id,$employee_id);
                    $manager_result = false;
                    break;
                }
            }

            //管理者締め可能
            if($manager_result){
                //締め状態を管理者締めに更新
                $model_t002_attendance_information->updateCloseStateId($employee_id, $target_start_serial, $target_end_serial, CLOSE_STATE_MANAGER);
                $model_t003_attendance_aggregate->updateCloseStateId($employee_id, $target_term, CLOSE_STATE_MANAGER);
                $model_t003_attendance_aggregate->updateCloseManagerEmployeeId($employee_id, $login_employee_id, $target_term);
                Log::info(2, 2,"管理者締め完了",$employee_id);
            }else{
                // 本人締め処理を呼び出す
                Log::info(2, 2,"本人締め呼び出し",$employee_id);
                $this->autoCloseThemSelves($login_employee_id, $employee_id, $target_term, true);
            }
        }
    }

    /**
     * ステータスの状態をチェック
     */
    private function checkStateId($t002_attendance_info, $employee_id, $today_serial){
        $result = true;
        foreach($t002_attendance_info as $info)
        {
            //すべての日付が申請済みであることを確認
            //承認状態IDが「差戻」、「仮申請」がある場合は本人締めできない
            if(($info->approval_state_id == APPROVAL_STATE_REMAND)
            || ($info->approval_state_id == APPROVAL_STATE_TEMPORARY)){
                Log::info(2, 2,"本人締めエラー。すべての日付が申請されていることを確認してください。（承認状態IDが「差戻」、「仮申請」がある場合は本人締めできない）",$employee_id);
                $result = false;
                break;
            }
            //承認状態IDが「初期状態」
            else if($info->approval_state_id == APPROVAL_STATE_INITIAL){
                //出休IDが「通常勤務」かつ、{乖離なし、かつ、前日以前(自動申請状態)}以外は本人締めできない
                if($info->work_holiday_id == WORK_HOLIDAY_NORMAL){
                    if(!(($info->violation_warning_id == VIOLATION_WARNING_NORMAL) && ($info->attendance_date < $today_serial))){
                        Log::info(2, 2,"本人締めエラー。すべての日付が申請されていることを確認してください。（出休IDが「通常勤務」かつ、{乖離なし、かつ、前日以前(自動申請状態)}以外は本人締めできない）",$employee_id);
                        $result = false;
                        break;
                    }
                }
            }
        }
        return $result;
    }
    /**
     * 締め状態のチェックを行う。
     */
    public function checkCloseStatus($employee_id, $target_term, $close_state_id){
        $isClosedStatus = true;

        //t003_勤怠集計データ取得
        $model_t003_attendance_aggregate = new t003_attendance_aggregate();
        $t003_attendance_aggregate = $model_t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $target_term);

        // T003の存在チェック
        if(!$t003_attendance_aggregate){
            Log::info(2, 2,"T003が存在していません ",$employee_id);
            return false;
        }

        if($t003_attendance_aggregate->close_state_id>=$close_state_id){
            Log::info(2, 2,"既に締めが完了している状態です ".$close_state_id,$employee_id);
            $isClosedStatus = false;
        }

        return $isClosedStatus;
    }
}
