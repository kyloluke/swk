<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t017_daily_report extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't017_daily_report';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id', 
        'work_date',
        'work_no',
        'work_time_start',
        'work_time_end',
        'theme_id',
        'work_content',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "daily_report_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function getData()
    {
        $t017DailyReport = DB::table($this->table)->get();

        return $t017DailyReport;
    }
    
    /**
     * 対象社員の指定期間内の日報情報を取得
     */
    public function getDailyReportWithinTerm($employee_id, $firstday, $lastday)
    {
        return DB::table($this->table)
            ->select('daily_report_id','employee_id', 'work_date', 'work_no', 'work_time_start','work_time_end','theme_id','work_content','detail_no','is_delete')
            ->where('employee_id', $employee_id)
            ->whereBetween('work_date', [$firstday, $lastday])
            ->where('is_delete', 0)
            ->orderBy('work_date')
            ->orderBy('work_no')
            ->get();
    }

    /**
     * 指定日の日報情報を取得
     */
    public function t017GetDailyReport($employee_id, $work_date)
    {
        return DB::table($this->table)
            ->select('daily_report_id', 'work_no', 'work_time_start','work_time_end','theme_id','work_content','detail_no')
            ->where('employee_id', $employee_id)
            ->where('work_date', $work_date)
            ->where('is_delete', 0)
            ->orderBy('work_no', 'asc')
            ->get();
    }

    /**
     * 該当日の日報を削除して、追加
     */
    public function deleteAndUpdate($employee_id, $work_date, $report_array)
    {
        //一旦すべてに削除フラグ立てる
        DB::table($this->table)
        ->where('employee_id', $employee_id)
        ->where('work_date', $work_date)
        ->update([
            'is_delete' => 1
        ]);
        //idがあるものはUpdate、ないものはInsert
        foreach($report_array as $report)
        {
            if($report['daily_report_id'] != 0)
            {
                //Update
                DB::table($this->table)
                    ->where('daily_report_id', $report['daily_report_id'])
                    ->update(
                    [
                        'work_no' => $report['work_no'],
                        'work_time_start' => $report['work_time_start_serial'],
                        'work_time_end' => $report['work_time_end_serial'],
                        'theme_id' => $report['theme_id'],
                        'work_content' => $report['work_content'],
                        'is_delete' => 0,
                    ]
                );
            }
            else
            {
                //Insert
                DB::table($this->table)->insert(
                    [
                        'employee_id' => $employee_id,
                        'work_date' => $work_date,
                        'work_no' => $report['work_no'],
                        'work_time_start' => $report['work_time_start_serial'],
                        'work_time_end' => $report['work_time_end_serial'],
                        'theme_id' => $report['theme_id'],
                        'work_content' => $report['work_content'],
                    ]
                );
            }
        }
    }
}
