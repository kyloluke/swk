<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m018_approval_state extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm018_approval_state';
    // 更新可能な項目の設定
    protected $fillable = [
        'approval_state_class',
        'approval_state_name',
        'approval_state_short_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "approval_state_id";
    public function getData()
    {
        $m018ApprovalState = DB::table($this->table)->get();

        return $m018ApprovalState;
    }

    /**
     * Key：ID、Value：承認区分のショートネーム
     * とした配列を取得
     */
    public function getApprovalStateNameList()
    {
        $data_array = DB::table($this->table)
            ->select('approval_state_id', 'approval_state_short_name')
            ->where('is_delete', 0)
            ->get();
        $ret_array = array();
        foreach($data_array as $data)
        {
            $ret_array += array($data->approval_state_id => $data->approval_state_short_name);
        }
        return $ret_array;
    }
}
