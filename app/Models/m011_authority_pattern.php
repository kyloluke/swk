<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m011_authority_pattern extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm011_authority_pattern';
    // 更新可能な項目の設定
    protected $fillable = [
        'proxy_input_menu',
        'proxy_attendance_input',
        'proxy_input_target_setting',
        'attendance_admin_menu',
        'attendance_admin_inquiry_approval',
        'attendance_admin_work_vacation_management',
        'attendance_admin_daily_report',
        'attendance_admin_approval_target_setting',
        'office_menu',
        'office_attendance_input',
        'office_work_vacation_management',
        'office_closing',
        'office_vacation_management',
        'office_general_search',
        'office_master_management',
        'general_affairs_menu',
        'general_affairs_attendance_input',
        'general_affairs_work_vacation_management',
        'general_affairs_closing',
        'general_affairs_general_search',
        'general_affairs_vacation_management',
        'general_affairs_data_input_output',
        'general_affairs_master_management',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "authority_pattern_id";
    public function getData()
    {
        $m011AuthorityPattern = DB::table($this->table)->get();

        return $m011AuthorityPattern;
    }

    /**
     * 権限パターンIDを取得
     */
    public function checkId($authority_pattern_id)
    {
        return DB::table($this->table)
            ->select('authority_pattern_id')
            ->where('authority_pattern_id', $authority_pattern_id)
            ->where('is_delete', 0)
            ->first();
    }
}
