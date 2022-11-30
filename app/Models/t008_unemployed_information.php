<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t008_unemployed_information extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't008_unemployed_information';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_information_id',
        'target_date',
        'unemployed_no',
        'unemployed_id',
        'unemployed_time', 
        'unemployed_time_start',
        'unemployed_time_end',
        'request_reason',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "unemployed_information_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function unemployed()
    {
        return $this->belongsTo('App\Models\m031_unemployed', 'unemployed_id');
    }
    public function getData()
    {
        $t008UnemployedInformation = DB::table($this->table)->get();

        return $t008UnemployedInformation;
    }

    /**
     * 登録済みの不就業情報を勤務情報IDから取得 
     */
    public function getUnployedInformation($attendance_information_id)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 不就業情報を更新
     */
    public function applyUnemployedInformation($attendance_information)
    {
        //同一日の不就業情報があれば削除
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information['attendance_information_id'])
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);

        //新規で登録
        foreach($attendance_information['unemployed_array_valid'] as $unemployed)
        {
            //空の場合は何もしない
            if($unemployed['unemployed_id'] == null || $unemployed['unemployed_id'] == 0)
            {
                continue;
            }

            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information['attendance_information_id'],
                    'target_date' => $attendance_information['attendance_date'],
                    'unemployed_id' => $unemployed['unemployed_id'],
                    'unemployed_time' => $unemployed['unemployed_time'],
                    'unemployed_time_start' => $unemployed['start_time'],
                    'unemployed_time_end' => $unemployed['end_time'],
                    'request_reason' => $unemployed['request_reason'],
                ]
            );
        }
    }

    /**
     * 不就業情報を更新（一括申請）
     */
    public function applyUnemployedInformationWithId($attendance_information, $attendance_information_id,$attendance_date)
    {
        //同一日の不就業情報があれば削除
        DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);

        //新規で登録
        foreach($attendance_information['unemployed_array_valid'] as $unemployed)
        {
            //空の場合は何もしない
            if($unemployed['unemployed_id'] == null || $unemployed['unemployed_id'] == 0)
            {
                continue;
            }

            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information_id,
                    'target_date' => $attendance_date,
                    'unemployed_id' => $unemployed['unemployed_id'],
                    'unemployed_time' => $unemployed['unemployed_time'],
                    'unemployed_time_start' => $unemployed['start_time'],
                    'unemployed_time_end' => $unemployed['end_time'],
                    'request_reason' => $unemployed['request_reason'],
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
     * 対象社員の指定期間内の不就業情報を取得
     */
    public function getUnemployedInformationWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('unemployed_information_id','attendance_information_id','target_date','unemployed_no','unemployed_id','unemployed_time', 'unemployed_time_start','unemployed_time_end','request_reason')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->whereBetween('target_date', [$firstday_of_month, $lastday_of_month])
            ->get();
    }

    /**
     * 対象社員の指定日付の不就業情報を取得
     */
    public function getUnemployedInformationByDate($employee_id, $date)
    {
        return DB::table($this->table)
            ->select('unemployed_information_id','attendance_information_id','target_date','unemployed_no','unemployed_id','unemployed_time', 'unemployed_time_start','unemployed_time_end','request_reason')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->where('target_date', $date)
            ->get();
    }

    /**
     * 全社員の指定期間内の不就業情報を取得
     */
    public function getAllUnemployedInformationWithinTerm($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->where('is_delete', 0)
            ->whereBetween('target_date', [$firstday_of_month, $lastday_of_month])
            ->get();
    }

}
