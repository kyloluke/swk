<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
include_once(dirname(__FILE__).'/../Http/AppLibs/Const.php');

class t014_office_closing_status extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't014_office_closing_status';
    // 更新可能な項目の設定
    protected $fillable = [
        'office_id',
        'office_closing_year_month',
        'close_date_id',
        'closing_status_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "office_closing_status_id";
    public function office()
    {
        return $this->belongsTo('App\Models\m004_office', 'office_id');
    }
    public function close_date()
    {
        return $this->belongsTo('App\Models\m016_close_date', 'close_date_id');
    }
    public function getData()
    {
        $t014OfficeClosingStatus = DB::table($this->table)->get();

        return $t014OfficeClosingStatus;
    }
    /**
     * 対象期間、締め区分の締め状態を取得
     */
    public function getOfficeClosingStatusWithinTerm($target_term, $close_date_id)
    {
        return DB::table($this->table)
            ->select('office_id','office_closing_year_month','close_date_id','closing_status_class','detail_no','is_delete','created_user','updated_user')
            ->where('office_closing_year_month', $target_term)
            ->where('close_date_id', $close_date_id)
            ->get();
    }
    /**
     * 事業所締め状態を更新
     */
    public function updateOfficeClosingStatus($office_id, $target_term, $close_date_id, $closing_status_class)
    {
        return DB::table($this->table)
            ->where('office_id', $office_id)
            ->where('office_closing_year_month', $target_term)
            ->where('close_date_id', $close_date_id)
            ->update(['closing_status_class' => $closing_status_class]);
    }

    /**
     * 事業所締め状態を作成
     */
    public function createOfficeClosingStatus($office_id, $target_term, $close_date_id)
    {
        //存在チェック
        $existing = DB::table($this->table)
            ->where('office_id', $office_id)
            ->where('office_closing_year_month', $target_term)
            ->where('close_date_id', $close_date_id)
            ->first();

        //存在しない場合のみInsert
        if($existing == null)
        {
            DB::table($this->table)
                ->insert([
                    'office_id' => $office_id,
                    'office_closing_year_month' => $target_term,
                    'close_date_id' => $close_date_id,
                    'closing_status_class' => CLOSE_STATE_INITIAL,
                    'detail_no' => 0,
                    'is_delete' => 0,
                    'created_user' => "SYSTEM",
                    'updated_user' => "SYSTEM",
                ]);
        }
    }

}
