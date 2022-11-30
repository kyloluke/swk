<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t019_work_closing_belonging_office_history extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't019_work_closing_belonging_office_history';
    // 更新可能な項目の設定
    protected $fillable = [
        'employee_id', 
        'office_id',
        'office_code',
        'office_name',
        'office_short_name',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "work_closing_belonging_office_history_id";
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    public function office()
    {
        return $this->belongsTo('App\Models\m004_office', 'office_id');
    }
    public function getData()
    {
        $t019WorkClosingBelongingOfficeHistory = DB::table($this->table)->get();

        return $t019WorkClosingBelongingOfficeHistory;
    }

    /**
     * 履歴情報新規
     */
    public function insertData($targetEmployeeID,$office_id,$office_code,$office_name,$office_short_name,$valid_date_start,$valid_date_end,$detail_no,$employeeCode,$date)
    {
        return DB::table($this->table)
        ->insert([
            'employee_id' => $targetEmployeeID,
            'office_id' => $office_id,
            'office_code' => $office_code,
            'office_name' => $office_name,
            'office_short_name' => $office_short_name,
            'valid_date_start' => $valid_date_start,
            'valid_date_end' => $valid_date_end,
            'detail_no' => $detail_no,
            'is_delete' => 0,
            'created_user' => $employeeCode,
            'created_at' => $date, 
            'updated_user' => $employeeCode,
            'updated_at' => $date,
        ]);
    }
    /**
     * 一番大きい数のdetail_no取得
     */
    public function last_detail_no()
    {
        return DB::table($this->table)
            ->select('detail_no')
            ->orderBy('detail_no', 'desc')
            ->first();
    }
    
    /**
     * データ取得
     */
    public function getDataOfEmployee($targetEmployeeID)
    {
        return DB::table($this->table)
            ->select('work_closing_belonging_office_history_id', 'office_id', 'office_code', 'office_name', 'office_short_name', 'valid_date_start', 'valid_date_end')
            ->where('employee_id', $targetEmployeeID)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * データ取得（基準日指定）
     */
    public function getDataWithinTerm($targetEmployeeID, $targetDate)
    {
        return DB::table($this->table)
            ->select('office_id', 'office_code', 'office_name', 'office_short_name', 'valid_date_start', 'valid_date_end')
            ->where('employee_id', $targetEmployeeID)
            ->where('valid_date_start', '<=', $targetDate)
            ->where('valid_date_end', '>=', $targetDate)
            ->where('is_delete', 0)
            ->get();
    }
  
    /**
     * 過去のデータ更新
     */
    public function updatePreviousData($work_closing_belonging_office_history_id, $update_start_serial, $update_end_serial, $userCode, $update_date)
    {
        DB::table($this->table)
            ->where('work_closing_belonging_office_history_id', $work_closing_belonging_office_history_id)
            ->update([
                    'valid_date_start' => $update_start_serial,
                    'valid_date_end' => $update_end_serial,
                    'updated_user' => $userCode,
                    'updated_at' => $update_date
        ]);
        if($update_end_serial < $update_start_serial){ //終了が開始より前になったら削除
            DB::table($this->table)
            ->where('work_closing_belonging_office_history_id', $work_closing_belonging_office_history_id)
            ->update([
                    'is_delete' => 1,
            ]);
        }
    }
}
