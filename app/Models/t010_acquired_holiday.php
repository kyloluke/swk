<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t010_acquired_holiday extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't010_acquired_holiday';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'attendance_information_id',
        'acquired_holiday_date',
        'unemployed_id',
        'acquired_holiday_unit',
        'acquired_holiday_days',
        'acquired_holiday_half_days',
        'acquired_holiday_time',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "acquired_holiday_id";
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
        $t010AcquiredHoliday = DB::table($this->table)->get();

        return $t010AcquiredHoliday;
    }

    /**
     * 登録済みの休暇情報を勤務情報IDから取得 
     */
    public function getAcauiredHoliday($attendance_information_id)
    {
        return DB::table($this->table)
            ->where('attendance_information_id', $attendance_information_id)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 休暇取得情報年間
     */
    public function getUnemployedInformationWithinTerm($employee_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->whereBetween('acquired_holiday_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 休暇取得情報を更新
     */
    public function applyAcauiredHoliday($attendance_information)
    {
        //同一勤務情報の休暇取得情報があれば削除
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
            //単位と登録数
            $acquired_holiday_unit = $unemployed['unemployed_info']['unemployed_take_unit_class'];
            switch($acquired_holiday_unit)
            {
                case 1:
                    $acquired_holiday_days = 1;
                    $acquired_holiday_half_days = 0;
                    $acquired_holiday_time = 0;
                    break;
                case 2:
                    $acquired_holiday_days = 0;
                    $acquired_holiday_half_days = 1;
                    $acquired_holiday_time = 0;
                    break;
                case 3:
                    $acquired_holiday_days = 0;
                    $acquired_holiday_half_days = 0;
                    $acquired_holiday_time = $unemployed['unemployed_time'];
                    break;
                case 4:
                    $acquired_holiday_days = 0;
                    $acquired_holiday_half_days = 0;
                    $acquired_holiday_time = 0;
                    break;
                }

            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information['attendance_information_id'],
                    'acquired_holiday_date' => $attendance_information['attendance_date'],
                    'unemployed_id' => $unemployed['unemployed_id'],
                    'acquired_holiday_unit' => $acquired_holiday_unit,
                    'acquired_holiday_days' => $acquired_holiday_days,
                    'acquired_holiday_half_days' => $acquired_holiday_half_days,
                    'acquired_holiday_time' => $acquired_holiday_time,
                ]
            );
        }
    }

    /**
     * 休暇取得情報を更新（一括申請）
     */
    public function applyAcauiredHolidayWithId($attendance_information, $attendance_information_id,$attendance_date)
    {
        //同一勤務情報の休暇取得情報があれば削除
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
            //単位と登録数
            $acquired_holiday_unit = $unemployed['unemployed_info']['unemployed_take_unit_class'];
            switch($acquired_holiday_unit)
            {
                case 1:
                    $acquired_holiday_days = 1;
                    $acquired_holiday_half_days = 0;
                    $acquired_holiday_time = 0;
                    break;
                case 2:
                    $acquired_holiday_days = 0;
                    $acquired_holiday_half_days = 1;
                    $acquired_holiday_time = 0;
                    break;
                case 3:
                    $acquired_holiday_days = 0;
                    $acquired_holiday_half_days = 0;
                    $acquired_holiday_time = $unemployed['unemployed_time'];
                    break;
                case 4:
                    $acquired_holiday_days = 0;
                    $acquired_holiday_half_days = 0;
                    $acquired_holiday_time = 0;
                    break;
                }

            DB::table($this->table)
                ->insert([
                    'employee_id' => $attendance_information['employee_id'],
                    'attendance_information_id' => $attendance_information_id,
                    'acquired_holiday_date' => $attendance_date,
                    'unemployed_id' => $unemployed['unemployed_id'],
                    'acquired_holiday_unit' => $acquired_holiday_unit,
                    'acquired_holiday_days' => $acquired_holiday_days,
                    'acquired_holiday_half_days' => $acquired_holiday_half_days,
                    'acquired_holiday_time' => $acquired_holiday_time,
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
            ->where('acquired_holiday_date', $target_date)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);
        return;
    }

    /**
     * 全社員の指定期間の休暇情報を取得
     */
    public function getAcquiredHolidayWithinTerm($firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->whereBetween('acquired_holiday_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->orderBy('acquired_holiday_date', 'asc')
            ->get();
    }

}
