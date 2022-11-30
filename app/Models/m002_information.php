<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\AppLibs\CommonFunctions;

class m002_information extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm002_information';
    // 更新可能な項目の設定
    protected $fillable = [
        'information_type_id',
        'display_company_id',
        'display_office_id',
        'display_dept_id',
        'information_subject_name',
        'information_contants',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];

    protected $primaryKey = "information_id";
    public function getData()
    {
        $data = DB::table($this->table)->get();

        return $data;
    }
    public function information_type()
    {
        return $this->belongsTo('App\Models\m001_information_type', 'information_type_id');
    }

    public function getInformationData($information_type_id, $office_id, $dept_id)
    {
        $cf = new CommonFunctions();
        $today = $cf->getTodaySerial();
        $m002Informationdata = m002_information::where('information_type_id', $information_type_id) //選択したインフォメーション種別と一致
            ->where('valid_date_start', '<=', $today)           //当日以前の日付が登録されているもの
            ->where('valid_date_end', '>=', $today)             //当日以降の日付が登録されているもの
            ->where('is_delete', 0)                             //削除フラグが0
            ->where('display_office_id', $office_id)
            ->where('display_dept_id', $dept_id)
            ->get();      
        $m002Informationdata_dept = m002_information::where('information_type_id', $information_type_id) //選択したインフォメーション種別と一致
            ->where('valid_date_start', '<=', $today)           //当日以前の日付が登録されているもの
            ->where('valid_date_end', '>=', $today)             //当日以降の日付が登録されているもの
            ->where('is_delete', 0)                             //削除フラグが0
            ->where('display_office_id', 1)
            ->where('display_dept_id', 0)
            ->get();
        $m002Informationdata_office = m002_information::where('information_type_id', $information_type_id) //選択したインフォメーション種別と一致
            ->where('valid_date_start', '<=', $today)           //当日以前の日付が登録されているもの
            ->where('valid_date_end', '>=', $today)             //当日以降の日付が登録されているもの
            ->where('is_delete', 0)                             //削除フラグが0
            ->where('display_office_id', 0)
            ->get();
        $ret_array = [];
        foreach($m002Informationdata as $info)
        {
            $ret_array[] = $info;
        }
        foreach($m002Informationdata_dept as $info)
        {
            $ret_array[] = $info;
        }
        foreach($m002Informationdata_office as $info)
        {
            $ret_array[] = $info;
        }
        return $ret_array;
    }
    public function countInformation($information_type_id, $office_id, $dept_id)
    {
        $cf = new CommonFunctions();
        $today = $cf->getTodaySerial();
        $count = DB::table($this->table)
            ->where('information_type_id', $information_type_id)
            ->where('display_office_id', $office_id)
            ->where('display_dept_id', $dept_id)
            ->where('valid_date_start', '<=', $today)
            ->where('valid_date_end', '>=', $today)
            ->where('is_delete', 0)  
            ->count();
        $count += DB::table($this->table)
            ->where('information_type_id', $information_type_id)
            ->where('display_office_id', 1)
            ->where('display_dept_id', 0)
            ->where('valid_date_start', '<=', $today)
            ->where('valid_date_end', '>=', $today)
            ->where('is_delete', 0)  
            ->count();
        $count += DB::table($this->table)
            ->where('information_type_id', $information_type_id)
            ->where('display_office_id', 0)
            ->where('valid_date_start', '<=', $today)
            ->where('valid_date_end', '>=', $today)
            ->where('is_delete', 0)  
            ->count();
        return $count;
    }
    public function getInformationDataWithoutType($office_id, $dept_id)
    {
        $cf = new CommonFunctions();
        $today = $cf->getTodaySerial();
        $m002Informationdata = DB::table($this->table) //選択したインフォメーション種別と一致
            ->where('valid_date_start', '<=', $today)           //当日以前の日付が登録されているもの
            ->where('valid_date_end', '>=', $today)             //当日以降の日付が登録されているもの
            ->where('is_delete', 0)                             //削除フラグが0
            ->where('display_office_id', $office_id)
            ->where('display_dept_id', $dept_id)
            ->get();      
        $m002Informationdata_dept = DB::table($this->table) //選択したインフォメーション種別と一致
            ->where('valid_date_start', '<=', $today)           //当日以前の日付が登録されているもの
            ->where('valid_date_end', '>=', $today)             //当日以降の日付が登録されているもの
            ->where('is_delete', 0)                             //削除フラグが0
            ->where('display_office_id', 1)
            ->where('display_dept_id', 0)
            ->get();
        $m002Informationdata_office = DB::table($this->table)//選択したインフォメーション種別と一致
            ->where('valid_date_start', '<=', $today)           //当日以前の日付が登録されているもの
            ->where('valid_date_end', '>=', $today)             //当日以降の日付が登録されているもの
            ->where('is_delete', 0)                             //削除フラグが0
            ->where('display_office_id', 0)
            ->get();
        $ret_array = [];
        foreach($m002Informationdata as $info)
        {
            $ret_array[] = $info;
        }
        foreach($m002Informationdata_dept as $info)
        {
            $ret_array[] = $info;
        }
        foreach($m002Informationdata_office as $info)
        {
            $ret_array[] = $info;
        }
        return $ret_array;
    }

    public function getAllInformation($companyID)
    {
        return DB::table($this->table)
        ->where('is_delete', 0)
        ->where('display_company_id', $companyID)
        ->get();
    }

    /**
     * インフォメーションの新規登録
     */
    public function insertInformation($company_id, $information_type_id, $office_id, $information_subject_name, $information_contants, $valid_date_start, $valid_date_end)
    {
        return DB::table($this->table)->insert(
            ['information_type_id' => $information_type_id, 'display_company_id' => $company_id, 'display_office_id' => $office_id, 'display_dept_id' => 0, 'information_subject_name' => $information_subject_name, 'information_contants' => $information_contants, 'valid_date_start' => $valid_date_start, 'valid_date_end' => $valid_date_end]
        );
    }

    /**
     * インフォメーションの更新
     */
    public function updateInformation($information_id, $information_type_id, $office_id, $information_subject_name, $information_contants, $valid_date_start, $valid_date_end)
    {
        return DB::table($this->table)
            ->where('information_id', $information_id)
            ->update(['information_type_id' => $information_type_id, 'display_office_id' => $office_id, 'display_dept_id' => 0, 'information_subject_name' => $information_subject_name, 'information_contants' => $information_contants, 'valid_date_start' => $valid_date_start, 'valid_date_end' => $valid_date_end]);
    }

    /**
     * インフォメーションの削除
     */
    public function deleteInformation($information_id)
    {
        return DB::table($this->table)
            ->where('information_id', $information_id)
            ->where('is_delete', 0)
            ->update(['is_delete' => 1]);
    }
}
