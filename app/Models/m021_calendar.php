<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m021_calendar extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm021_calendar';
    // 更新可能な項目の設定
    protected $fillable = [
        'calendar_name',
        'start_month',
        'is_holiday_rest',
        'monday_work_holiday_id',
        'tuesday_work_holiday_id',
        'wednesday_work_holiday_id',
        'thursday_work_holiday_id',
        'friday_work_holiday_id',
        'saturday_work_holiday_id',
        'sunday_work_holiday_id',
        'company_id',
        'is_invalid',
        'origin_calendar_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "calendar_id";
    public function calendar_setting()
    {
        return $this->hasMany('App\Models\m022_calendar_setting', 'calendar_id');
    }
    public function getData()
    {
        $m021Calendar = DB::table($this->table)->get();

        return $m021Calendar;
    }
    public function getName($calendar_id)
    {
        $calendar_name = DB::table($this->table)
            ->select(
                'calendar_name'
            )
            ->where('calendar_id', $calendar_id)
            ->where('is_delete', 0)
            ->first();
        if(empty($calendar_name))
        {
            return 'データなし';
        }
        else
        {
            return $calendar_name->calendar_name;
        }
    }

    /**
     * カレンダIDを取得
     */
    public function checkId($calendar_id)
    {
        return DB::table($this->table)
            ->select('calendar_id')
            ->where('calendar_id', $calendar_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * カレンダ一覧を取得
     */
    public function getCalendarList()
    {
        $calendar_list = DB::table($this->table)
            ->where('is_delete', 0)
            ->get();
        return $calendar_list;
    }

    /**
     * カレンダ新規作成
     */
    public function insertCalendar($calendar_name, $start_month, $company_id, $is_holiday_rest, $monday_work_holiday_id, $tuesday_work_holiday_id, $wednesday_work_holiday_id, $thursday_work_holiday_id, $friday_work_holiday_id, $saturday_work_holiday_id, $sunday_work_holiday_id, $user)
    {
        $detail_no = $this->lastCalendar()->detail_no + 1;
        return DB::table($this->table)->insert(
            ['calendar_name' => $calendar_name, 'start_month' => $start_month, 'is_holiday_rest' => $is_holiday_rest, 'monday_work_holiday_id' => $monday_work_holiday_id, 'tuesday_work_holiday_id' => $tuesday_work_holiday_id, 'wednesday_work_holiday_id' => $wednesday_work_holiday_id, 'thursday_work_holiday_id' => $thursday_work_holiday_id, 'friday_work_holiday_id' => $friday_work_holiday_id, 'saturday_work_holiday_id' => $saturday_work_holiday_id, 'sunday_work_holiday_id' => $sunday_work_holiday_id, 'company_id' => $company_id, 'origin_calendar_id' => 0, 'detail_no' => $detail_no, 'is_delete' => 0, 'created_user' => $user, 'updated_user' => $user]
        );
    }

    /**
     * 最新新規作成したのを取得
     */
    public function lastCalendar()
    {
        return DB::table($this->table)
            ->where('is_delete', 0)
            ->orderBy('calendar_id', 'desc')
            ->first();
    }
}
