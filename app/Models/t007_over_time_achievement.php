<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t007_over_time_achievement extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't007_over_time_achievement';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_information_id',
        'target_date',
        'over_time_class_id',
        'over_time_start',
        'over_time_end',
        'over_time_rest_time',
        'over_time_midnight_rest_time',
        'deduction_time',
        'deduction_reason_id',
        'deduction_reason',
        'over_time_reason',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "over_time_achievement_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function deduction_reason()
    {
        return $this->belongsTo('App\Models\m015_deduction_reason', 'deduction_reason_id');
    }
    public function over_time_class()
    {
        return $this->belongsTo('App\Models\m014_over_time_class_id', 'over_time_class_id');
    }
    public function getData()
    {
        $t007OverTimeAchievement = DB::table($this->table)->get();

        return $t007OverTimeAchievement;
    }
    /**
     * 登録済みの時間外情報を勤務情報IDから取得 
     */
    public function getOverTimeAchievementInformation($attendance_information_id)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 時間外情報を更新
     */
    public function applyOverTimeAchievementInformation($attendance_information)
    {
        //同一日の時間外情報があれば削除
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);

        //新規で登録
        foreach($attendance_information['over_time_class_array_valid'] as $over_time)
        {
            //空の場合は何もしない
            if($over_time['over_time_class_id'] == null || $over_time['over_time_class_id'] == 0)
            {
                continue;
            }
            //nullの可能性があるものを空文字に
            if(!array_key_exists('deduction_reason', $over_time) || $over_time['deduction_reason'] == null)
            {
                $over_time['deduction_reason'] = '';
            }
            if(!array_key_exists('deduction_time', $over_time) || $over_time['deduction_time'] == null)
            {
                $over_time['deduction_time'] = 0;
            }

            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information['attendance_information_id'],
                    'target_date' => $attendance_information['attendance_date'],
                    'over_time_class_id' => $over_time['over_time_class_id'],
                    'over_time_start' => $over_time['over_time_start'],
                    'over_time_end' => $over_time['over_time_end'],
                    'over_time_rest_time' => $over_time['over_time_rest_time'],
                    'over_time_midnight_rest_time' => $over_time['over_time_midnight_rest_time'],
                    'deduction_time' => $over_time['deduction_time'],
                    'deduction_reason_id' => $over_time['deduction_reason_id'],
                    'deduction_reason' => $over_time['deduction_reason'],
                    'over_time_reason' => $over_time['over_time_reason'],
                ]
            );
        }
    }

    /**
     * 時間外情報を更新（一括申請）
     */
    public function applyOverTimeAchievementInformationWithId($attendance_information, $attendance_information_id,$attendance_date)
    {
        //同一日の時間外情報があれば削除
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);

        //新規で登録
        foreach($attendance_information['over_time_class_array_valid'] as $over_time)
        {
            //空の場合は何もしない
            if($over_time['over_time_class_id'] == null || $over_time['over_time_class_id'] == 0)
            {
                continue;
            }
            //nullの可能性があるものを空文字に
            if(!array_key_exists('deduction_reason', $over_time) || $over_time['deduction_reason'] == null)
            {
                $over_time['deduction_reason'] = '';
            }
            if(!array_key_exists('deduction_time', $over_time) || $over_time['deduction_time'] == null)
            {
                $over_time['deduction_time'] = 0;
            }

            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information_id,
                    'target_date' => $attendance_date,
                    'over_time_class_id' => $over_time['over_time_class_id'],
                    'over_time_start' => $over_time['over_time_start'],
                    'over_time_end' => $over_time['over_time_end'],
                    'over_time_rest_time' => $over_time['over_time_rest_time'],
                    'over_time_midnight_rest_time' => $over_time['over_time_midnight_rest_time'],
                    'deduction_time' => $over_time['deduction_time'],
                    'deduction_reason_id' => $over_time['deduction_reason_id'],
                    'deduction_reason' => $over_time['deduction_reason'],
                    'over_time_reason' => $over_time['over_time_reason'],
                ]
            );
        }
    }
    /**
     * 指定の社員、日付のデータを削除
     */
    public function deleteData($employee_id, $target_date)
    {
        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('target_date', $target_date)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);
        return;
    }

    /**
     * 対象社員の指定期間内の時間外実績情報を取得
     */
    public function getOverTimeAchievementWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('attendance_information_id','target_date','over_time_class_id','over_time_start','over_time_end','over_time_rest_time','over_time_midnight_rest_time','deduction_time','deduction_reason_id','deduction_reason','over_time_reason')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->whereBetween('target_date', [$firstday_of_month, $lastday_of_month])
            ->get();
    }

    /**
     * 指定期間内の時間外実績情報を取得
     */
    public function getOverTimeAchievementByTerm($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('employee_id','attendance_information_id','target_date','over_time_class_id','over_time_start','over_time_end','over_time_rest_time','over_time_midnight_rest_time','deduction_time','deduction_reason_id','deduction_reason','over_time_reason')
            ->where('is_delete', 0)
            ->whereBetween('target_date', [$firstday_of_month, $lastday_of_month])
            ->get();
    }


}
