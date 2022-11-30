<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t002_attendance_information extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't002_attendance_information';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'violation_warning_id',
        'work_holiday_id',
        'approval_state_id',
        'work_achievement_id',
        'work_zone_id',
        'unemployed_id',
        'work_zone_time_start',
        'work_zone_time_end',
        'work_time_start',
        'work_time_end',
        'actual_work_time',
        'web_punch_clock_time_start',
        'web_punch_clock_time_end',
        'other_system_time_start',
        'other_system_time_end',
        'statutory_working_time',
        'non_statutory_working_time',
        'midnight_time',
        'holiday_midnight_work_break_time',
        'holiday_work_break_time',
        'midnight_break_time',
        'break_time',
        'deduction_time',
        'unemployed_time',
        'holiday_work_time',
        'holiday_midnight_work_time',
        'absent_time',
        'substitute_holiday_reason',
        'information',
        'remand_reason',
        'approval_request_date',
        'input_employee_id',
        'approval_employee_id',
        'close_state_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "attendance_information_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function violation_warning()
    {
        return $this->belongsTo('App\Models\m037_violation_warning', 'violation_warning_id');
    }
    public function work_holiday()
    {
        return $this->belongsTo('App\Models\m027_work_holiday', 'work_holiday_id');
    }
    public function approval_state()
    {
        return $this->belongsTo('App\Models\m018_approval_state', 'approval_state_id');
    }
    public function work_achievement()
    {
        return $this->belongsTo('App\Models\m030_work_achievement', 'work_achievement_id');
    }
    public function work_zone()
    {
        return $this->belongsTo('App\Models\m023_work_zone', 'work_zone_id');
    }
    public function unemployed()
    {
        return $this->belongsTo('App\Models\m031_unemployed', 'unemployed_id');
    }
    public function over_time_achievement()
    {
        return $this->hasMany('App\Models\t007_over_time_achievement', 'over_time_achievement_id');
    }
    public function unemployed_information()
    {
        return $this->hasMany('App\Models\t008_unemployed_information', 'unemployed_information_id');
    }
    public function acquired_holiday()
    {
        return $this->hasMany('App\Models\t010_acquired_holiday', 'acquired_holiday_id');
    }
    public function holiday_worker_information()
    {
        return $this->hasMany('App\Models\t011_holiday_worker_information', 'holiday_worker_information_id');
    }
    public function getData()
    {
        $t002AttendanceInformation = DB::table($this->table)->get();

        return $t002AttendanceInformation;
    }

    public function getAttendanceInformations($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
                        ->select('violation_warning_id')
                        ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
                        ->get();
    }

    /**
     * 対象社員の指定期間内の出勤情報を取得
     */
    public function getAttendanceInformationWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('attendance_information_id', 'attendance_date', 'violation_warning_id', 'work_holiday_id', 'approval_state_id','work_achievement_id','work_zone_id', 'unemployed_id','work_time_start','work_time_end','actual_work_time','web_punch_clock_time_start','web_punch_clock_time_end','statutory_working_time','non_statutory_working_time','midnight_time','deduction_time','holiday_work_time','absent_time','substitute_holiday_reason','information','input_employee_id','approval_employee_id','close_state_id','holiday_midnight_work_time')
            ->where('employee_id', $employee_id)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->orderBy('attendance_date', 'asc')
            ->get();
    }

    /**
     * 対象社員の指定期間内の不就業情報を取得
     */
    public function getAttendanceInformationWithinTermUnemployee($employee_id, $firstday_of_month, $lastday_of_month, $unemployed_id)
    {
        return DB::table($this->table)
        ->select('attendance_information_id', 'attendance_date', 'violation_warning_id', 'work_holiday_id', 'approval_state_id','work_achievement_id','work_zone_id', 'unemployed_id','work_time_start','work_time_end','actual_work_time','web_punch_clock_time_start','web_punch_clock_time_end','statutory_working_time','non_statutory_working_time','midnight_time','deduction_time','holiday_work_time','absent_time','substitute_holiday_reason','information','input_employee_id','approval_employee_id','close_state_id','holiday_midnight_work_time')
        ->where('employee_id', $employee_id)
        ->where('unemployed_id',$unemployed_id)
        ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
        ->orderBy('attendance_date', 'asc')
        ->get();
    }

    /**
     * 指定期間内の出勤情報を取得
     */
    public function getAttendanceInformationByTerm($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->orderBy('attendance_date', 'asc')
            ->get();
    }

    /**
     * 日付と社員IDから勤務情報を取得
     */
    public function getAttendanceInformationByDate($employee_id, $target_date)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $target_date)
            ->first();
    }
    /**
     * 対象社員の指定期間内の出勤情報を取得
     */
    public function getFirstAttendanceInformationWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('attendance_date', 'violation_warning_id', 'work_holiday_id', 'approval_state_id','work_achievement_id','work_zone_id', 'unemployed_id', 'work_time_start','work_time_end','actual_work_time','web_punch_clock_time_start','web_punch_clock_time_end','statutory_working_time','non_statutory_working_time','midnight_time','deduction_time','holiday_work_time','absent_time','information','input_employee_id','approval_employee_id')
            ->where('employee_id', $employee_id)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->first();
    }
    /**
     * 対象社員のWEB打刻時間更新
     */
    public function updateAttendanceInformationWebPunchClockTime($employee_id, $attendance_date, $clocking_in_out_id, $web_punch_clock_time, $input_class)
    {
        if($clocking_in_out_id == 1){
            $existing = null;
            if($input_class == 0){
                //存在チェック
                $existing = DB::table($this->table)
                    ->where('employee_id', $employee_id)
                    ->where('attendance_date', $attendance_date)
                    ->where('web_punch_clock_time_start', '<>',0)
                    ->first();
            }
            //存在しない場合のみInsert
            if($existing == null)
            {
                return DB::table($this->table)
                ->where('employee_id', $employee_id)
                ->where('attendance_date', $attendance_date)
                ->update(['web_punch_clock_time_start' => $web_punch_clock_time]);
            }
        }elseif($clocking_in_out_id == 2){
            return DB::table($this->table)
                ->where('employee_id', $employee_id)
                ->where('attendance_date', $attendance_date)
                ->update(['web_punch_clock_time_end' => $web_punch_clock_time]);
        }

    }
    /**
     * 対象データのWeb打刻を削除
     */
    public function deleteWebPunchClockTime($employee_id, $attendance_date, $clocking_in_out_id)
    {
        if($clocking_in_out_id == 1){
            return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->update(['web_punch_clock_time_start' => null]);
        }elseif($clocking_in_out_id == 2){
            return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->update(['web_punch_clock_time_end' => null]);
        }
    }

    /**
     * 対象社員の違反警告ID更新
     */
    public function updateAttendanceInformationViolationWarningId($employee_id, $attendance_date, $violation_warning_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->update(['violation_warning_id' => $violation_warning_id]);
    }
    /**
     * 勤務情報の更新
     */
    public function applyAttendanceInformation($attendance_information,$type,$substitute_holiday_reason)
    {
        //振替休日の指定があった場合は、振替先の休出IDを変更
        if($attendance_information['work_achievement_id'] == 7)
        {
            $sub_info = $this->getAttendanceInformationByDate($attendance_information['employee_id'], $attendance_information['substitute_holiday_date']);
            DB::table($this->table)
                ->where('attendance_information_id', $sub_info->attendance_information_id)
                ->update(['work_holiday_id' => $attendance_information['work_holiday_id'],
                    'substitute_holiday_reason' => $substitute_holiday_reason,
                    'information' => '',
                    'work_zone_id' => 0, 
                    'work_time_start' => 0, 
                    'work_time_end' => 0, 
                    'work_zone_time_start' => 0, 
                    'work_zone_time_end' => 0, 
                    'actual_work_time' => 0,
                    'work_achievement_id' => 9]);
        }

        //nullの可能性があるものを空文字に置き換え
        if(!$attendance_information['information'])
        {
            $attendance_information['information'] = "";
        }
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->update([
                'violation_warning_id' => 1, //正常に戻す
                'work_holiday_id' => $attendance_information['work_holiday_id'], //出休
                'approval_state_id' => $type, //申請中
                'work_achievement_id' =>  $attendance_information['work_achievement_id'], //実績
                'work_zone_id' => $attendance_information['work_zone_id'], //勤務帯
                'unemployed_id' => $attendance_information['unemployed_id'], //勤務帯 １つ目を登録
                'work_zone_time_start' => $attendance_information['work_zone_time_start'], //勤務時間,
                'work_zone_time_end' => $attendance_information['work_zone_time_end'], //勤務時間,
                'work_time_start' => $attendance_information['work_time_start'], //申請時間,
                'work_time_end' => $attendance_information['work_time_end'], //申請時間,
                'actual_work_time' => $attendance_information['actual_work_time'], //実働時間,
                'statutory_working_time' => $attendance_information['statutory_working_time'], //法定内時間外,
                'non_statutory_working_time' => $attendance_information['non_statutory_working_time'], //法定外時間外,
                'midnight_time' => $attendance_information['midnight_time'], //深夜時間
                'holiday_midnight_work_break_time' => $attendance_information['holiday_midnight_work_break_time'], //休日深夜休憩
                'holiday_work_break_time' => $attendance_information['holiday_work_break_time'], //休日休憩
                'midnight_break_time' => $attendance_information['midnight_break_time'], //深夜休憩
                'break_time' => $attendance_information['break_time'], //休憩時間
                'deduction_time' => $attendance_information['deduction_time'], //控除時間
                'unemployed_time' => $attendance_information['unemployed_time'], //不就業時間
                'holiday_work_time' => $attendance_information['holiday_work_time'], //休日時間
                'holiday_midnight_work_time' => $attendance_information['holiday_midnight_work_time'], //休日深夜
                'absent_time' => $attendance_information['absent_time'], //欠勤時間
                'information' => $attendance_information['information'], //連絡事項・事由
                'approval_request_date' => $attendance_information['approval_request_date'], //申請日
                'input_employee_id' => $attendance_information['input_employee_id'], //申請者
                'approval_employee_id' => 0, //承認者
                'substitute_holiday_reason' => '',
            ]
        );
    }

    /**
     * 勤務情報の更新(一括申請・一括申請せずに保存)
     * @param type approval_state_idの値に当てはまる
     */
    public function applyAttendanceInformationWithId($attendance_information,$attendance_information_id, $type)
    {
        //nullの可能性があるものを空文字に置き換え
        if(!$attendance_information['information'])
        {
            $attendance_information['information'] = "";
        }
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->update([
                'violation_warning_id' => 1, //正常に戻す
                'work_holiday_id' => $attendance_information['work_holiday_id'], //出休
                'approval_state_id' => $type, //申請中・申請せずに保存
                'work_achievement_id' =>  $attendance_information['work_achievement_id'], //実績
                'work_zone_id' => $attendance_information['work_zone_id'], //勤務帯
                'unemployed_id' => $attendance_information['unemployed_id'], //勤務帯 １つ目を登録
                'work_zone_time_start' => $attendance_information['work_zone_time_start'], //勤務時間,
                'work_zone_time_end' => $attendance_information['work_zone_time_end'], //勤務時間,
                'work_time_start' => $attendance_information['work_time_start'], //申請時間,
                'work_time_end' => $attendance_information['work_time_end'], //申請時間,
                'actual_work_time' => $attendance_information['actual_work_time'], //実働時間,
                'statutory_working_time' => $attendance_information['statutory_working_time'], //法定内時間外,
                'non_statutory_working_time' => $attendance_information['non_statutory_working_time'], //法定外時間外,
                'midnight_time' => $attendance_information['midnight_time'], //深夜時間
                'holiday_midnight_work_break_time' => $attendance_information['holiday_midnight_work_break_time'], //休日深夜休憩
                'holiday_work_break_time' => $attendance_information['holiday_work_break_time'], //休日休憩
                'midnight_break_time' => $attendance_information['midnight_break_time'], //深夜休憩
                'break_time' => $attendance_information['break_time'], //休憩時間
                'deduction_time' => $attendance_information['deduction_time'], //控除時間
                'unemployed_time' => $attendance_information['unemployed_time'], //不就業時間
                'holiday_work_time' => $attendance_information['holiday_work_time'], //休日時間
                'holiday_midnight_work_time' => $attendance_information['holiday_midnight_work_time'], //休日深夜
                'absent_time' => $attendance_information['absent_time'], //欠勤時間
                'information' => $attendance_information['information'], //連絡事項・事由
                'approval_request_date' => $attendance_information['approval_request_date'], //申請日
                'input_employee_id' => $attendance_information['input_employee_id'], //申請者
                'approval_employee_id' => 0, //承認者
                'substitute_holiday_reason' => '',
            ]
        );
    }
    
    /**
     * 勤務情報の更新（仮申請）
     */
    public function provisionalApplyAttendanceInformation($attendance_information)
    {
        //振替休日の指定があった場合は、振替先の休出IDを変更
        if($attendance_information['work_achievement_id'] == 7)
        {
            $sub_info = $this->getAttendanceInformationByDate($attendance_information['employee_id'], $attendance_information['substitute_holiday_date']);
            DB::table($this->table)
                ->where('attendance_information_id', $sub_info->attendance_information_id)
                ->update(['work_holiday_id' => $attendance_information['work_holiday_id'],
                    'information' => $attendance_information['information'],
                    'work_achievement_id' => 9]);
        }

        //nullの可能性があるものを空文字に置き換え
        if(!$attendance_information['substitute_holiday_reason'])
        {
            $attendance_information['substitute_holiday_reason'] = "";
        }
        if(!$attendance_information['information'] || $attendance_information['work_achievement_id'] == 7)
        {
            $attendance_information['information'] = "";
        }
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->update([
                'violation_warning_id' => 1, //正常に戻す
                'work_holiday_id' => 1, //正常出勤に戻す
                'approval_state_id' => 5, //仮申請中
                'work_achievement_id' =>  $attendance_information['work_achievement_id'], //実績
                'work_zone_id' => $attendance_information['work_zone_id'], //勤務帯
                'unemployed_id' => $attendance_information['unemployed_id'], //勤務帯 １つ目を登録
                'work_zone_time_start' => $attendance_information['work_zone_time_start'], //勤務時間,
                'work_zone_time_end' => $attendance_information['work_zone_time_end'], //勤務時間,
                'work_time_start' => $attendance_information['work_time_start'], //申請時間,
                'work_time_end' => $attendance_information['work_time_end'], //申請時間,
                'actual_work_time' => $attendance_information['actual_work_time'], //実働時間,
                'statutory_working_time' => $attendance_information['statutory_working_time'], //法定内時間外,
                'non_statutory_working_time' => $attendance_information['non_statutory_working_time'], //法定外時間外,
                'midnight_time' => $attendance_information['midnight_time'], //深夜時間
                'holiday_midnight_work_break_time' => $attendance_information['holiday_midnight_work_break_time'], //休日深夜休憩
                'holiday_work_break_time' => $attendance_information['holiday_work_break_time'], //休日休憩
                'midnight_break_time' => $attendance_information['midnight_break_time'], //深夜休憩
                'break_time' => $attendance_information['break_time'], //休憩時間
                'deduction_time' => $attendance_information['deduction_time'], //控除時間
                'unemployed_time' => $attendance_information['unemployed_time'], //不就業時間
                'holiday_work_time' => $attendance_information['holiday_work_time'], //休日時間
                'holiday_midnight_work_time' => $attendance_information['holiday_midnight_work_time'], //休日深夜
                'absent_time' => $attendance_information['absent_time'], //欠勤時間
                'substitute_holiday_reason' => $attendance_information['substitute_holiday_reason'], //休振事由
                'information' => $attendance_information['information'], //連絡事項・事由
                'approval_request_date' => $attendance_information['approval_request_date'], //申請日
                'input_employee_id' => $attendance_information['input_employee_id'], //申請者
                'approval_employee_id' => 0, //承認者
            ]
        );
    }
    /**
     * 承認処理
     */
    public function approve($attendance_information, $approval_employee_id)
    {
        //承認状態変えるだけ
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->update([
                'approval_state_id' => 3, //承認
                'remand_reason' => '', //差戻事由削除
                'approval_employee_id' => $approval_employee_id, //承認社員ID
            ]
        );
    }
    /**
     * 差戻処理
     */
    public function remand($attendance_information)
    {
        //差戻事由登録と承認状態変更
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->update([
                'approval_state_id' => 4, //承認
                'remand_reason' => $attendance_information['remand_reason']
            ]
        );
    }
    /**
     * 承認解除
     */
    public function approveUnrecognized($attendance_information)
    {
        //差戻事由登録と承認状態変更
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->update(
                [
                    'approval_state_id' => 2,
                    'approval_employee_id' => 0
                ]
            );
    }

    /**
     * 対象データの仮申請処理
     */
    public function updateAssumedApprovalStateIdAndInformation($attendance_information_id,$updated_user,$updated_at,$information)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->update(['approval_state_id' => 5,
                        'information' => $information,
                        'violation_warning_id' => 1,
                        'updated_user' => $updated_user,
                        'updated_at' => $updated_at]);
    }

    /**
     * 振替休日の取り消し処理
     */
    public function cancelApprovalStateIdAndInformation($attendance_information_id,$updated_user,$updated_at)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->update(['approval_state_id' => 1,
                        'information' => '',
                        'updated_user' => $updated_user,
                        'updated_at' => $updated_at]);
    }
    
    /**
     * 勤務テーブル新規作成
     */
    public function createAttendanceInformation($employee_id, $violation_warning_id, $approval_state_id, $attendance_date, $work_holiday_id, $work_achievement_id, $work_zone_id, $work_zone_time_start, $work_zone_time_end, $work_time_start, $work_time_end, $actual_work_time, $substitute_holiday_reason, $information, $remand_reason)
    {
        //存在チェック
        $existing = DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->first();
        //存在しない場合のみInsert
        if($existing == null)
        {
            DB::table($this->table)
                ->insert([
                    'employee_id' => $employee_id,
                    'attendance_date' => $attendance_date,
                    'violation_warning_id' => $violation_warning_id,
                    'approval_state_id' => $approval_state_id,
                    'work_holiday_id' => $work_holiday_id,
                    'work_achievement_id' => $work_achievement_id,
                    'work_zone_id' => $work_zone_id,
                    'work_zone_time_start' => $work_zone_time_start,
                    'work_zone_time_end' => $work_zone_time_end,
                    'work_time_start' => $work_time_start,
                    'work_time_end' => $work_time_end,
                    'actual_work_time' => $actual_work_time,
                    'substitute_holiday_reason' => $substitute_holiday_reason,
                    'information' => $information,
                    'remand_reason' => $remand_reason,
                    'close_state_id' => 1,
                ]);
        }
    }
    /**
     * 勤務情報レコーダーを作成する
     */
    public function createAttendanceInformationForce(
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
        $web_punch_clock_time_start,
        $web_punch_clock_time_end
        )
    {
        //状態チェック
        $existing = DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->first();
        if($existing != null && 1 < $existing->approval_state_id)
        {
            return;
        }

        DB::table($this->table)
            ->updateOrInsert(
                [
                    'employee_id' => $employee_id,
                    'attendance_date' => $attendance_date
                ],
                [
                    'employee_id' => $employee_id,
                    'attendance_date' => $attendance_date,
                    'violation_warning_id' => $violation_warning_id,
                    'approval_state_id' => $approval_state_id,
                    'work_holiday_id' => $work_holiday_id,
                    'work_achievement_id' => $work_achievement_id,
                    'work_zone_id' => $work_zone_id,
                    'work_zone_time_start' => $work_zone_time_start,
                    'work_zone_time_end' => $work_zone_time_end,
                    'work_time_start' => $work_time_start,
                    'work_time_end' => $work_time_end,
                    'actual_work_time' => $actual_work_time,
                    'substitute_holiday_reason' => $substitute_holiday_reason,
                    'information' => $information,
                    'remand_reason' => $remand_reason,
                    'close_state_id' => 1,
                    'web_punch_clock_time_start' => $web_punch_clock_time_start,
                    'web_punch_clock_time_end' => $web_punch_clock_time_end,
            ]);
    }
    /**
     * 勤務テーブル更新
     */
    public function updateAttendanceInformation(
        $employee_id,
        $approval_state_id,
        $attendance_date,
        $work_holiday_id,
        $work_achievement_id,
        $work_zone_id,
        $unemployed_id,
        $work_time_start,
        $work_time_end,
        $actual_work_time,
        $statutory_working_time,
        $non_statutory_working_time,
        $midnight_time,
        $break_time,
        $midnight_break_time,
        $holiday_work_break_time,
        $holiday_midnight_work_break_time,
        $deduction_time,
        $unemployed_time,
        $holiday_work_time,
        $holiday_midnight_work_time,
        $absent_time,
        $substitute_holiday_reason,
        $information,
        $remand_reason,
        $approval_request_date,
        $input_employee_id,
        $approval_employee_id,
        $updated_user,
        $updated_at)
    {
        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->update([
                    'work_holiday_id' => $work_holiday_id,
                    'approval_state_id' => $approval_state_id,
                    'work_achievement_id' => $work_achievement_id,
                    'work_zone_id' => $work_zone_id,
                    'unemployed_id' => $unemployed_id,
                    'work_time_start' => $work_time_start,
                    'work_time_end' => $work_time_end,
                    'actual_work_time' => $actual_work_time,
                    'statutory_working_time' => $statutory_working_time,
                    'non_statutory_working_time' => $non_statutory_working_time,
                    'midnight_time' => $midnight_time,
                    'break_time' => $break_time,
                    'midnight_break_time' => $midnight_break_time,
                    'holiday_work_break_time' => $holiday_work_break_time,
                    'holiday_midnight_work_break_time' => $holiday_midnight_work_break_time,
                    'deduction_time' => $deduction_time,
                    'unemployed_time' => $unemployed_time,
                    'holiday_work_time' => $holiday_work_time,
                    'holiday_midnight_work_time' => $holiday_midnight_work_time,
                    'absent_time' => $absent_time,
                    'substitute_holiday_reason' => $substitute_holiday_reason,
                    'information' => $information,
                    'remand_reason' => $remand_reason,
                    'approval_request_date' => $approval_request_date,
                    'input_employee_id' => $input_employee_id,
                    'approval_employee_id' => $approval_employee_id,
                    'updated_user' => $updated_user,
                    'updated_at' => $updated_at
                ]);
    }
    /**
     * 対象社員の締め状態ID更新
     */
    public function updateCloseStateId($employee_id, $firstday_of_month, $lastday_of_month, $close_state_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->update(['close_state_id' => $close_state_id]);
    }

    /**
     * 指定実績データ数を取得 ()
     */
    public function countWithWorkAchievement($work_achievement_id,$employee_id)        
    {
        return DB::table($this->table)
            ->where('work_achievement_id', $work_achievement_id)
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->count();
    }

    /**
     * 対象社員指定期間のデータを取得
     */
    public function getDataInmonth($employee_id, $firstday_of_month, $lastday_of_month)        
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 対象社員の控除時間更新
     */
    public function updateDeductionTime($attendance_information_id,$deduction_time)
    {
        return DB::table($this->table)
        ->where('attendance_information_id', $attendance_information_id)
            ->update(['deduction_time' => $deduction_time]);
    }

    /**
     * 対象社員の欠勤時間更新
     */
    public function updateAbsentTime($attendance_information_id,$absent_time)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->update(['absent_time' => $absent_time]);
    }

    /**
     * 対象データの承認状態更新
     */
    public function updateApprovalStateId($attendance_information_id,$updated_user,$updated_at)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->update(['approval_state_id' => 2,
                        'updated_user' => $updated_user,
                        'updated_at' => $updated_at]);
    }

    /**
     * 対象データの承認状態更新
     */
    public function updateApprovalStateIdAndInformation($attendance_information_id,$updated_user,$updated_at,$information)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->update(['approval_state_id' => 2,
                        'information' => $information,
                        'violation_warning_id' => 1,
                        'updated_user' => $updated_user,
                        'updated_at' => $updated_at]);
    }

    /**
     * 対象社員の振替休日取り消し
     */
    public function updateSubstituteHoliday($employee_id,$substitute_holiday_date,$work_zone_id,$work_time_start,$work_time_end,$actual_work_time)
    {
        $sub_info = $this->getAttendanceInformationByDate($employee_id,$substitute_holiday_date);
        DB::table($this->table)
            ->where('attendance_information_id', $sub_info->attendance_information_id)
            ->update(['work_holiday_id' => 1,
                'approval_state_id' => 1,
                'substitute_holiday_reason' => "",
                'information' => "",
                'work_achievement_id' => 1,
                'work_zone_id' => $work_zone_id,
                'work_time_start' => $work_time_start, 
                'work_time_end' => $work_time_end, 
                'work_zone_time_start' => $work_time_start, 
                'work_zone_time_end' => $work_time_end, 
                'actual_work_time' => $actual_work_time,
                'violation_warning_id' => 1]);
    }

    /**
     * 対象社員の振替休日登録
     */
    public function insertSubstituteHoliday($employee_id,$substitute_holiday_date,$substitute_reason)
    {
        $sub_info = $this->getAttendanceInformationByDate($employee_id,$substitute_holiday_date);
        DB::table($this->table)
            ->where('attendance_information_id', $sub_info->attendance_information_id)
            ->where('work_achievement_id', 1)
            ->where('is_delete', 0)
            ->update(['information' => '',
                'substitute_holiday_reason' => $substitute_reason,
                'work_achievement_id' => 9,
                'violation_warning_id' => 1,
                'work_zone_id' => 0, 
                'work_time_start' => 0, 
                'work_time_end' => 0, 
                'work_zone_time_start' => 0, 
                'work_zone_time_end' => 0, 
                'actual_work_time' => 0,
                'work_holiday_id' => 2]);
    }

    /**
     * 出休変更処理
     */
    public function updateWorkHoliday($attendance_information, $work_zone_info, $start_end_time)
    {
        if($attendance_information == null || $attendance_information['work_achievement_id'] == null)
        {
            return false;
        }

        $work_zone_id = 0;
        $start_time = 0;
        $end_time = 0;
        $actual_work_time = 0;
        if($work_zone_info && $start_end_time)
        {
            $work_zone_id = $work_zone_info->work_zone_id;
            $start_time = $start_end_time->start_time;
            $end_time = $start_end_time->end_time;
            $actual_work_time = $work_zone_info->actual_work_time;
        }
        //勤務実績ID 出休ID 勤務帯ID 変更登録
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->update([
                'work_achievement_id' => $attendance_information['work_achievement_id'], //勤務実績ID
                'work_holiday_id' => $attendance_information['work_holiday_id'], //出休ID
                'work_zone_id' => $work_zone_id, //勤務帯ID
                'work_time_start' => $start_time, 
                'work_time_end' => $end_time, 
                'work_zone_time_start' => $start_time, 
                'work_zone_time_end' => $end_time, 
                'actual_work_time' => $actual_work_time,
                'violation_warning_id' => 1, //初期状態に戻す
            ]
        );
        return true;
    }

    /**
     * 当月以外の締めなかった「振休」の数
     */
    public function getSubstituteHolidayWithoutMonthCount($employee_id,$attendance_start_serial,$attendance_end_serial){
        $count = 0;
        $count += DB::table($this->table)
        ->where('work_achievement_id', 9)
        ->where('close_state_id', 1)
        ->where('employee_id', $employee_id)
        ->where('is_delete', 0)
        ->count();

        $count -= DB::table($this->table)
        ->where('work_achievement_id', 9)
        ->where('close_state_id', 1)
        ->where('employee_id', $employee_id)
        ->whereBetween('attendance_date', [$attendance_start_serial, $attendance_end_serial])
        ->where('is_delete', 0)
        ->count();
        return $count;
    }

    /**
     * 当月「振休」のデータ
     */
    public function getSubstituteHolidayWithinTerm($employee_id,$attendance_start_serial,$attendance_end_serial){

        return DB::table($this->table)
        ->where('work_achievement_id', 9)
        ->where('employee_id', $employee_id)
        ->whereBetween('attendance_date', [$attendance_start_serial, $attendance_end_serial])
        ->where('is_delete', 0)
        ->get();
    }
    /**
     * 対象社員の指定期間内の実働時間を取得
     */
    public function getActualWorkTimeWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        $attendance_information = DB::table($this->table)
            ->select('actual_work_time','holiday_work_time','holiday_midnight_work_time')
            ->where('employee_id', $employee_id)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->orderBy('attendance_date', 'asc')
            ->get();
        $actual_work_time = 0;
        foreach($attendance_information as $info){
            $actual_work_time += $info->actual_work_time + $info->holiday_work_time + $info->holiday_midnight_work_time;
        }
        return $actual_work_time;
    }

    /**
     * 対象社員の指定期間内の法定外休日勤務時間を取得
     */
    public function getHolidayWorkTimeWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        $attendance_information = DB::table($this->table)
            ->select('holiday_work_time','holiday_midnight_work_time')
            ->where('employee_id', $employee_id)
            ->where('work_holiday_id', 2)
            ->whereBetween('attendance_date', [$firstday_of_month, $lastday_of_month])
            ->orderBy('attendance_date', 'asc')
            ->get();
        $holiday_work_time = 0;
        foreach($attendance_information as $info){
            $holiday_work_time += $info->holiday_work_time + $info->holiday_midnight_work_time;
        }
        return $holiday_work_time;
    }

    /**
     * 当月振替休日全削除
     */
    public function deleteSubstituteHolidayWithinTerm($employee_id, $attendance_date, $work_zone_info, $start_end_time)
    {
        $work_zone_id = 0;
        $start_time = 0;
        $end_time = 0;
        $actual_work_time = 0;
        if($work_zone_info && $start_end_time)
        {
            $work_zone_id = $work_zone_info->work_zone_id;
            $start_time = $start_end_time->start_time;
            $end_time = $start_end_time->end_time;
            $actual_work_time = $work_zone_info->actual_work_time;
        }
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('attendance_date', $attendance_date)
            ->where('work_achievement_id', 9)
            ->where('is_delete', 0)
            ->update(['substitute_holiday_reason' => '',
                'work_zone_id' => $work_zone_id, //勤務帯ID
                'work_time_start' => $start_time, 
                'work_time_end' => $end_time, 
                'work_zone_time_start' => $start_time, 
                'work_zone_time_end' => $end_time, 
                'actual_work_time' => $actual_work_time,
                'violation_warning_id' => 1, //初期状態に戻す
                'work_achievement_id' => 1,
                'work_holiday_id' => 1]);
    }

    /**
     * 対象社員の退職日の翌日から登録されている最終日までを非在籍かつ承認済みに
     */
    public function updateRetirementDay($employeeID, $targetEmployeeID, $retirementDaySerial, $updateDateSerial)
    {
        $attendance_information = DB::table($this->table)
            ->select('attendance_information_id','approval_state_id')
            ->where('employee_id', $targetEmployeeID)
            ->where('is_delete', 0)
            ->where('attendance_date', '>=', $retirementDaySerial + 1)
            ->get();

        if($attendance_information){
            //申請されている日が1日でもあればfalse返す
            foreach($attendance_information as $info){
                if($info->approval_state_id != 1){
                    return false;
                }
            }

            DB::table($this->table)
                ->where('employee_id', $targetEmployeeID)
                ->where('work_holiday_id', 1)
                ->where('is_delete', 0)
                ->where('attendance_date', '>=', $retirementDaySerial + 1)
                ->update([
                    'work_achievement_id' => 8, //勤務実績ID
                    'work_zone_id' => 0, //勤務帯ID
                    'approval_state_id' => 3, //申請状態
                    'work_time_start' => 0,
                    'work_time_end' => 0,
                    'work_zone_time_start' => 0,
                    'work_zone_time_end' => 0,
                    'actual_work_time' => 0,
                    'approval_request_date' => $updateDateSerial,
                    'input_employee_id' => $employeeID,
                    'approval_employee_id' => $employeeID,
                    'violation_warning_id' => 1,
                ]);
        }

        return true;
    }

    /**
     * 勤務情報をアップデートする
     * @param Number attendance_information_id  
     * @param Array dataArr  対象カラムの配列
     */
    static public function specificColumnUpdate($attendance_information_id, $dataArr)
    {
        if(is_numeric($attendance_information_id) && is_array($dataArr) && count($dataArr) > 0) {
            static::where('attendance_information_id', $attendance_information_id)
                ->where('is_delete', 0)
                ->update($dataArr);
        }
    }
    
}
