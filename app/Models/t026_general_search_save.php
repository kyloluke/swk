<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class t026_general_search_save extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't026_general_search_save';
    // 更新可能な項目の設定
    protected $fillable = [
        'general_search_save_name',
        'share_class',
        'company_id',
        'employee_id',
        'unit_type',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user',
    ];
    protected $primaryKey = "general_search_save_id";

    /**
     * 指定条件での重複取得
     * 重複結果は重複＝重複対象ID　非重複＝0
     * 不正値の場合はnullを返す
     */
    public function checkIsDupulicateSaveName($save_name, $share_class, $company_id, $employee_id)
    {
        if($company_id == 0 || $employee_id == 0)
        {
            //会社ID or 社員ID未指定は不正
            return null;
        }
        //共通の場合は同一会社内で被りが無いかチェック
        switch($share_class)
        {
            //type0=共通の場合は同一会社内のtype0で検索
            case 0:
                $record = DB::table($this->table)
                    ->where('share_class', $share_class)
                    ->where('company_id', $company_id)
                    ->where('general_search_save_name', $save_name)
                    ->where('is_delete', 0)
                    ->first();
                if($record == null)
                {
                    return 0;
                }
                else
                {
                    return $record->general_search_save_id;
                }

            //type=1の場合は同一社員内で検索
            case 1:
                $record = DB::table($this->table)
                    ->where('share_class', $share_class)
                    ->where('employee_id', $employee_id)
                    ->where('general_search_save_name', $save_name)
                    ->where('is_delete', 0)
                    ->first();
                if($record == null)
                {
                    return 0;
                }
                else
                {
                    return $record->general_search_save_id;
                }
            
            //その他はエラー扱い
            default:
                return null;
        }
    }
    
    /**
     * 一覧取得
     * 共通と個人と両方取得
     */
    public function getGeneralSearchSaveList($company_id, $employee_id)
    {
        $commonList = DB::table($this->table)
            ->select('general_search_save_id', 'general_search_save_name')
            ->where('share_class', 0)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->get();
        $personalList = DB::table($this->table)
            ->select('general_search_save_id', 'general_search_save_name')
            ->where('share_class', 1)
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->get();
        return [
            'common_list' => $commonList,
            'personal_list' => $personalList,
        ];
    }

    /**
     * 名前変更
     */
    public function overwriteSaveName($save_id, $save_name)
    {
        return DB::table($this->table)
            ->where('general_search_save_id', $save_id)
            ->update(['general_search_save_name' => $save_name]);
    }
    
    /**
     * 新規作成
     */
    public function createGeneralSearchSave($save_name, $share_class, $company_id, $employee_id, $unit_type)
    {
        $id = DB::table($this->table)
            ->insertGetId([
                'general_search_save_name' => $save_name,
                'share_class' => $share_class,
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'unit_type' => $unit_type,
                'created_user' => $employee_id,
                'updated_user' => $employee_id,
            ]);
        return $id;
    }

    public function deleteGeneralSearchSave($delete_id, $employee_id)
    {
        date_default_timezone_set('Asia/Tokyo');
        $updated_at = date('Y-m-d H:i:s');
        return DB::table($this->table)
                ->where('general_search_save_id', $delete_id)
                ->update([
                    'is_delete' => 1,
                    'updated_user' => $employee_id,
                    'updated_at' => $updated_at
                ]);
    }
}
