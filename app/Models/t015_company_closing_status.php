<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
include_once(dirname(__FILE__).'/../Http/AppLibs/Const.php');

class t015_company_closing_status extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't015_company_closing_status';
    // 更新可能な項目の設定
    protected $fillable = [
        'company_id',
        'company_closing_year_month',
        'close_date_id',
        'closing_status_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "company_closing_status_id";
    public function company()
    {
        return $this->belongsTo('App\Models\m004_office', 'company_id');
    }
    public function close_date()
    {
        return $this->belongsTo('App\Models\m016_close_date', 'close_date_id');
    }
    public function getData()
    {
        $t015CompanyClosingStatus = DB::table($this->table)->get();

        return $t015CompanyClosingStatus;
    }
    /**
     * 対象期間、締め区分の締め状態を取得
     */
    public function getCompanyClosingStatusWithinTerm($company_id, $target_term, $close_date_id)
    {
        return DB::table($this->table)
            ->select('closing_status_class')
            ->where('company_id', $company_id)
            ->where('company_closing_year_month', $target_term)
            ->where('close_date_id', $close_date_id)
            ->first();
    }
    /**
     * 全社締め状態を更新
     */
    public function updateCompanyClosingStatus($company_id, $target_term, $close_date_id, $closing_status_class)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('company_closing_year_month', $target_term)
            ->where('close_date_id', $close_date_id)
            ->update(['closing_status_class' => $closing_status_class]);
    }

    /**
     * 全社締め状態を作成
     */
    public function createCompanyClosingStatus($company_id, $target_term, $close_date_id)
    {
        //存在チェック
        $existing = DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('company_closing_year_month', $target_term)
            ->where('close_date_id', $close_date_id)
            ->first();

        //存在しない場合のみInsert
        if($existing == null)
        {
            DB::table($this->table)
                ->insert([
                    'company_id' => $company_id,
                    'company_closing_year_month' => $target_term,
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
