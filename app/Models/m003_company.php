<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m003_company extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm003_company';
    // 更新可能な項目の設定
    protected $fillable = [
        'company_code',
        'company_name',
        'company_short_name',
        'beginning_month',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    // primary keyの変更
    protected $primaryKey = "company_id";
    public function getData()
    {
        $m003Companydata = DB::table($this->table)
            ->where("is_delete", 0)
            ->get();

        return $m003Companydata;
    }
    public function office()
    {
        return $this->hasMany('\App\Models\m004_office', 'company_id');
    }
    /**
     * 会社コードから会社IDを取得
     */
    public function getIdByCode($company_code)        
    {
        if($company_code === null)
        {
            return null;
        }
        $company_id = DB::table($this->table)
            ->select('company_id')
            ->where('company_code', $company_code)
            ->where('is_delete', 0)
            ->first();
        if($company_id === null)
        {
            return null;
        }
        else
        {
            return $company_id->company_id;
        }
    }
    /**
     * 企業情報の更新
     *
     * @param [type] $company_id
     * @param [type] $company_code
     * @param [type] $company_name
     * @param [type] $company_short_name
     * @param [type] $beginning_month
     * @param [type] $valid_date_start
     * @param [type] $valid_date_end
     * @return boolean 成功したかどうか
     */
    public function updateCompany($company_id, $company_code, $company_name, $company_short_name, $beginning_month, $valid_date_start, $valid_date_end)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->update([
                'company_code' => $company_code,
                'company_name' => $company_name,
                'company_short_name' => $company_short_name,
                'beginning_month' => $beginning_month,
                'valid_date_start' => $valid_date_start,
                'valid_date_end' => $valid_date_end,
            ]
        );
    }
    /**
     * 企業作成
     * 企業コードに被りがあると、登録NG
     *
     * @param [type] $company_code
     * @param [type] $company_name
     * @param [type] $company_short_name
     * @param [type] $beginning_month
     * @param [type] $valid_date_start
     * @param [type] $valid_date_end
     * @return boolean 成功したかどうか
     */
    public function createCompany($company_code, $company_name, $company_short_name, $beginning_month, $valid_date_start, $valid_date_end)
    {
        //company_codeの被りはNG
        $company_count = DB::table($this->table)
            ->where('company_code', $company_code)
            ->count();
        if(0 < $company_count)
        {
            return false;
        }
        return DB::table($this->table)
            ->insertGetId([
                'company_code' => $company_code,
                'company_name' => $company_name,
                'company_short_name' => $company_short_name,
                'beginning_month' => $beginning_month,
                'valid_date_start' => $valid_date_start,
                'valid_date_end' => $valid_date_end,
            ]
        );
    }
}
