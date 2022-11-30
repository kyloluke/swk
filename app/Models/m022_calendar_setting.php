<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m022_calendar_setting extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm022_calendar_setting';
    // 更新可能な項目の設定
    protected $fillable = [
        'calendar_id',
        'calendar_setting_year',
        'calendar_date',
        'work_holiday_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "calendar_setting_id";
    public function work_holiday()
    {
        return $this->belongsTo('App\Models\m027_work_holiday', 'work_holiday_id');
    }
    public function getData()
    {
        $m022CalendarSetting = DB::table($this->table)->get();

        return $m022CalendarSetting;
    }
    public function getCalendarSettingByDate($calendar_id, $calendar_date)
    {
        return DB::table($this->table)
            ->select('work_holiday_id')
            //->where('calendar_id', $calendar_id) //なぜかこれがあると取得できないのでコメントアウト
            ->where('calendar_date', $calendar_date)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 指定期間内の休出IDを取得
     */
    public function getCalendarSettingWithinTerm($calendar_id, $firstday_of_month, $lastday_of_month)
    {
        return DB::table($this->table)
            ->select('work_holiday_id', 'calendar_date')
            ->where('calendar_id', $calendar_id)
            ->whereBetween('calendar_date', [$firstday_of_month, $lastday_of_month])
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 指定年度のカレンダを取得
     */
    public function getCalendarSettingByYear($calendar_id, $calendar_setting_year)
    {
        return DB::table($this->table)
            ->where('calendar_id', $calendar_id)
            ->where('calendar_setting_year', $calendar_setting_year)
            ->where('is_delete', 0)
            ->orderBy('calendar_date')
            ->get();
    }

    /**
     * カレンダ設定編集
     */
    public function updateCalendarSetting($calendar_setting_id, $work_holiday_id)
    {
        return DB::table($this->table)
            ->where('calendar_setting_id', $calendar_setting_id)
            ->where('is_delete', 0)
            ->update(['work_holiday_id' => $work_holiday_id]);
    }
    
    /**
     * カレンダ設定新規作成
     */
    public function insertCalendarSetting($calendar_date, $work_holiday_id, $calendar_setting_year, $calendar_id, $user)
    {
        $detail_no = $this->lastDetailNo()->detail_no + 1;
        return DB::table($this->table)->insert(
            ['calendar_id' => $calendar_id, 'calendar_setting_year' => $calendar_setting_year, 'calendar_date' => $calendar_date, 'work_holiday_id' => $work_holiday_id, 'detail_no' => $detail_no, 'is_delete' => 0, 'created_user' => $user, 'updated_user' => $user]
        );
    }

    /**
     * 最新detail_noを取得
     */
    public function lastDetailNo()
    {
        return DB::table($this->table)
            ->select('detail_no')
            ->orderBy('detail_no', 'desc')
            ->first();
    }

}
