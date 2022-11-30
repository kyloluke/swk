<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t005_set_approval_target extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't005_set_approval_target';
    // 更新可能な項目の設定
    protected $fillable = [
        'approver_id',
        'approved_person_id',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "set_approval_target_id";
    public function approver()
    {
        return $this->belongsTo('App\Models\m007_employee', 'approver_id');
    }
    public function approved_person()
    {
        return $this->belongsTo('App\Models\m007_employee', 'approved_person_id');
    }
    public function getData()
    {
        $t005SetApprovalTarget = DB::table($this->table)->get();

        return $t005SetApprovalTarget;
    }
    /**
     * 承認者ID、基準日から被承認者IDを取得 (is_deleteは0が登録されているもの)
     */
    public function getTargetID($approver_id, $today_serial)        
    {
        $approvedPersonId = DB::table($this->table)
            ->select('approved_person_id')
            ->where('approver_id', $approver_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();

        return $approvedPersonId;
    }
    /**
     * 承認者ID、基準日からID(ソート用)、被承認者IDを取得 (is_deleteは0が登録されているもの)
     */
    public function getSortIDTargetID($approver_id, $today_serial)        
    {
        $approvedPersonId = DB::table($this->table)
            ->select('approved_person_id','set_approval_target_id')
            ->where('approver_id', $approver_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();

        return $approvedPersonId;
    }

    public function getInputAgentWithinTerm($employee_id, $inputagent_date_serial)
    {
        $target_array = DB::table($this->table)
            ->select('approved_person_id')
            ->where('approver_id', $employee_id)
            ->where('valid_date_start','<=', $inputagent_date_serial)
            ->where('valid_date_end','>=', $inputagent_date_serial)
            ->where('is_delete', 0)
            ->get();
        $ret_array = [];
        foreach($target_array as $target)
        {
            $ret_array[] = ['employee_id' => $target->approved_person_id];
        }
        return $ret_array;
    }

    /**
     * 承認者ID、基準日から被承認者IDを取得 (is_deleteは0が登録されているもの)
     */
    public function getAllApproved($today_serial)        
    {
        return DB::table($this->table)
            ->select('approver_id','approved_person_id')
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * データ数を取得
     */
    public function countApproved()        
    {
        return DB::table($this->table)
            ->count();
    }

    /**
     * 未削除データ数を取得 (is_deleteが0)
     */
    public function countApprovedUndeleted($inputDate)        
    {
        return DB::table($this->table)
            ->where('valid_date_start', '<=', $inputDate)
            ->where('valid_date_end', '>=', $inputDate)
            ->where('is_delete', 0)
            ->count();
    }

    /**
     * データをインサートします
     */
    public function setData($detail_no,$approver_id,$approved_person_id,$valid_date_start,$valid_date_end,$employeeCode,$date)
    {
        return DB::table($this->table)->insert([
            'approver_id' => $approver_id, 
            'approved_person_id' => $approved_person_id, 
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
     * データ更新（終了日に指定された日より後のデータの終了日を指定日にセット）
     */
    public function updateApprover($approver_id, $valid_date_end, $employee_code, $date)
    {
        return DB::table($this->table)
            ->where('approver_id', $approver_id)
            ->where('valid_date_end', '>', $valid_date_end)
            ->where('is_delete', 0)
            ->update([
                'valid_date_end' => $valid_date_end,
                'updated_user' => $employee_code,
                'updated_at' => $date,
            ]);
    }

    /**
     * データ更新（終了日に指定された日より後のデータの終了日を指定日にセット）
     */
    public function updateApproverAlluser($valid_date_end, $employee_code, $date)
    {
        return DB::table($this->table)
            ->where('valid_date_end', '>', $valid_date_end)
            ->where('is_delete', 0)
            ->update([
                'valid_date_end' => $valid_date_end,
                'updated_user' => $employee_code,
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
            ->where('is_delete', 0)
            ->orderBy('detail_no', 'desc')
            ->first();
    }

    /**
     * データ更新
     */
    public function changeValidDate($approver_id, $approved_person_id, $inputDate, $employee_code, $date)
    {
        return DB::table($this->table)
            ->where('approver_id', $approver_id)
            ->where('approved_person_id', $approved_person_id)
            ->where('valid_date_start', '<=', $inputDate)
            ->where('valid_date_end', '>=', $inputDate)
            ->where('is_delete', 0)
            ->update([
                'valid_date_end' => $inputDate-1,
                'updated_user' => $employee_code,
                'updated_at' => $date,
            ]);
    }
}
