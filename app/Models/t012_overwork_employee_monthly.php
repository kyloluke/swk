<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t012_overwork_employee_monthly extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't012_overwork_employee_monthly';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'target_year_month',
        'violation_warning_id',
        'overwork_time',
        'holiday_work_days',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "overwork_employee_monthly_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function violation_warning()
    {
        return $this->belongsTo('App\Models\m037_violation_warning', 'violation_warning_id');
    }
    public function getData()
    {
        $t012OverworkEmployeeMonthly = DB::table($this->table)->get();

        return $t012OverworkEmployeeMonthly;
    }
}
