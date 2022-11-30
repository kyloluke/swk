<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m046_grant_paid_leave_type extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm046_grant_paid_leave_type';
    // 更新可能な項目の設定
    protected $fillable = [
        'grant_paid_leave_type_name',
        'grant_paid_leave_month',
        'grant_paid_leave_day',
        'manegement_target_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "grant_paid_leave_type_id";

    public function getGrantPaidLeaveTypeList()
    {
        $grant_paid_leave_type_list = DB::table($this->table)
            ->select('grant_paid_leave_type_id','grant_paid_leave_type_name')
            ->where('is_delete', 0)
            ->get();
        return $grant_paid_leave_type_list;
    }
}
