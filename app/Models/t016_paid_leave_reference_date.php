<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t016_paid_leave_reference_date extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't016_paid_leave_reference_date';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id',
        'paid_leave_reference_date',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "paid_leave_reference_date_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function getData()
    {
        $t016PaidLeaveReferenceDate = DB::table($this->table)->get();

        return $t016PaidLeaveReferenceDate;
    }
}
