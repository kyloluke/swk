<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t011_holiday_worker_information extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't011_holiday_worker_information';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_information_id',
        'holiday_work_date',
        'work_achievement_id',
        'achievement_time',
        'holiday_work_reason',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "holiday_worker_information_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function work_achievement()
    {
        return $this->belongsTo('App\Models\m030_work_achievement', 'work_achievement_id');
    }
    public function getData()
    {
        $t011HolidayWorkerInformation = DB::table($this->table)->get();

        return $t011HolidayWorkerInformation;
    }

    /**
     * 勤務入力から休暇取得情報を更新
     */
    public function applyHolidayWorkerInformation($attendance_information)
    {
        //同一勤務情報の休暇取得情報があれば削除
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);
        //実績が4～7の場合は休日出勤、登録
        $work_id = $attendance_information['work_achievement_id'];
        if($work_id  == 4 || $work_id == 5 || $work_id == 6 || $work_id == 7)
        {
            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information['attendance_information_id'],
                    'holiday_work_date' => $attendance_information['attendance_date'],
                    'work_achievement_id' => $attendance_information['work_achievement_id'],
                    'achievement_time' => $attendance_information['holiday_work_time'] + $attendance_information['holiday_midnight_work_time'],
                    'holiday_work_reason' => $attendance_information['information'],
                ]
            );
        }
    }

    /**
     * 勤務入力から休暇取得情報を更新（一括申請）
     */
    public function applyHolidayWorkerInformationWithId($attendance_information, $attendance_information_id,$attendance_date)
    {
        //同一勤務情報の休暇取得情報があれば削除
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);
        //実績が4～7の場合は休日出勤、登録
        $work_id = $attendance_information['work_achievement_id'];
        if($work_id  == 4 || $work_id == 5 || $work_id == 6 || $work_id == 7)
        {
            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information_id,
                    'holiday_work_date' => $attendance_date,
                    'work_achievement_id' => $attendance_information['work_achievement_id'],
                    'achievement_time' => $attendance_information['holiday_work_time'] + $attendance_information['holiday_midnight_work_time'],
                    'holiday_work_reason' => $attendance_information['information'],
                ]
            );
        }
    }

    /**
     * 対象社員の指定期間内の出勤情報を取得
     */
    public function getHolidayWorkerInformationWithinTerm($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->whereBetween('holiday_work_date', [$firstday_of_month, $lastday_of_month])
            ->orderBy('holiday_work_date', 'asc')
            ->where('is_delete', 0)
            ->get();
    }
}
