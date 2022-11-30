<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class t027_general_search_save_param extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't027_general_search_save_param';
    // 更新可能な項目の設定
    protected $fillable = [
        'general_search_save_id',
        'kind',
        'columns',
        'detail_no',
        'type',
        'is_delete',
        'created_user',
        'updated_user',
    ];
    protected $primaryKey = "general_search_save_param_id";

    //上書き保存
    public function overwriteSaveParam($general_search_save_id, $target_list, $employee_id)
    {
        //すべて削除して再登録
        DB::table($this->table)
            ->where('general_search_save_id', $general_search_save_id)
            ->update(['is_delete' => 1]);
        $this->createSaveParam($general_search_save_id, $target_list, $employee_id);
    }
    
    //新規保存
    public function createSaveParam($general_search_save_id, $target_list, $employee_id)
    {
        foreach($target_list as $target)
        {
            $kind = $target['kind'];
            $cols = $target['columns'];
            $detail_no = $target['detail_no'];
            $type = $target['type'];
            for($i = 0; $i < count($cols); $i++)
            {
                DB::table($this->table)
                ->insert([
                    'general_search_save_id' => $general_search_save_id,
                    'kind' => $kind,
                    'columns' => $cols[$i],
                    'type' => $type[$i],
                    'detail_no' => $detail_no[$i],
                    'created_user' => $employee_id,
                    'updated_user' => $employee_id,
                ]);
            }
        }
    }

    //一覧取得
    public function getSaveParam($save_id)
    {
        return DB::table($this->table)
            ->where('general_search_save_id', $save_id)
            ->where('is_delete', 0)
            ->get();
    }

    //削除
    public function deleteSaveParam($save_id)
    {

    }
    // delete save param by general search id
    public function deleteSaveParamByGeneralSearchId($general_search_save_id)
    {
        //すべて削除して再登録
        return DB::table($this->table)
                ->where('general_search_save_id', $general_search_save_id)
                ->update(['is_delete' => 1]);
    }
}
