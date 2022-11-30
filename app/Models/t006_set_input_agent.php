<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class t006_set_input_agent extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't006_set_input_agent';
    // 更新可能な項目の設定
    protected $fillable = [
        'input_agent_id',
        'input_delegator_id',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "set_input_agent_id";
    public function input_agent()
    {
        return $this->belongsTo('App\Models\m007_employee', 'input_agent_id');
    }
    public function input_delegator()
    {
        return $this->belongsTo('App\Models\m007_employee', 'input_delegator_id');
    }
    public function getData()
    {
        $t006SetInputAgent = DB::table($this->table)->get();

        return $t006SetInputAgent;
    }
     /**
     * 代理入力者ID、基準日から被代理入力者IDを取得 (is_deleteは0が登録されているもの)
     */
    public function getTargetID($input_agent_id, $today_serial)        
    {
        $inputDelegatorId = DB::table($this->table)
            ->select('input_delegator_id')
            ->where('input_agent_id', $input_agent_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();

        return $inputDelegatorId;
    }

     /**
     * 代理入力者ID、基準日からID(ソート用)、被代理入力者IDを取得 (is_deleteは0が登録されているもの)
     */
    public function getSortIDTargetID($input_agent_id, $today_serial)        
    {
        $inputDelegatorId = DB::table($this->table)
            ->select('input_delegator_id','set_input_agent_id')
            ->where('input_agent_id', $input_agent_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();

        return $inputDelegatorId;
    }

    /**
     * 対象社員の指定期間内の代理入力対象者テーブルを取得
     */
    public function getInputAgentWithinTerm($employee_id, $inputagent_date_serial)
    {
        $target_array = DB::table($this->table)
            ->select('input_delegator_id')
            ->where('input_agent_id', $employee_id)
            ->where('valid_date_start','<=', $inputagent_date_serial)
            ->where('valid_date_end','>=', $inputagent_date_serial)
            ->where('is_delete', 0)
            ->get();
            
        $ret_array = [];
        foreach($target_array as $target)
        {
            $ret_array[] = ['employee_id' => $target->input_delegator_id];
        }
        return $ret_array;
    }

    public function getAllAgent($today_serial)        
    {
        return DB::table($this->table)
            ->select('input_agent_id','input_delegator_id')
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * データ数を取得
     */
    public function countAgent()        
    {
        return DB::table($this->table)
            ->count();
    }

    /**
     * 未削除データ数を取得 (is_deleteが0)
     */
    public function countAgentUndeleted($inputDate)        
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
    public function setData($detail_no,$input_agent_id,$input_delegator_id,$valid_date_start,$valid_date_end,$employeeCode,$date)
    {
        return DB::table($this->table)->insert([
            'input_agent_id' => $input_agent_id, 
            'input_delegator_id' => $input_delegator_id, 
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
    public function updateInputAgent($input_agent_id, $valid_date_end, $employee_code, $date)
    {
        return DB::table($this->table)
            ->where('input_agent_id', $input_agent_id)
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
    public function updateInputAgentAlluser($valid_date_end, $employee_code, $date)
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
    public function changeValidDate($input_agent_id, $input_delegator_id, $inputDate, $employee_code, $date)
    {
        return DB::table($this->table)
            ->where('input_agent_id', $input_agent_id)
            ->where('input_delegator_id', $input_delegator_id)
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
