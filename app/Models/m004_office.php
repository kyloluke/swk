<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m004_office extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm004_office';
    // 更新可能な項目の設定
    protected $fillable = [
        'company_id',
        'office_code',
        'office_name',
        'office_short_name',
        'paid_take_unit_class',
        'over_time_calc_class',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    // primary keyの変更
    protected $primaryKey = "office_id";

    /**
     * 会社IDから一覧を取得
     */
    public function getAllData($company_id)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * マスタデータを変更する
     * 使用可否は変更しないが、company_id=0のものを変更するときは親マスタIDを持たせたレコードを複製して編集する
     */
    public function updateOrInsertMasterData($dataArray,$isNew,$userCode,$company_id){
        $primaryKey = $this->getKeyName();
        $define = $this->getMasterDataDefine();
        //必須項目のチェック
        if(!isset($dataArray[$primaryKey]) && !$isNew)
        {
            return false;
        }
        //define指定項目のチェック
        foreach($define as $item)
        {
            if(!isset($dataArray[$item['column']]) && $item['required'])
            {
                return false;
            }
        }
        
        $id = $dataArray[$primaryKey];

        //該当のレコードを取得
        $record = DB::table($this->table)
            ->where($primaryKey, $id)
            ->first();
        if($record == null)
        {
            //無効なID指定の場合はfalseリターン
            return false;
        }
        $record = get_object_vars($record);
        $param = [];
        if($isNew){
            foreach($define as $item)
            {
                if(isset($dataArray[$item['column']]))
                {
                    $param[$item['column']] = $dataArray[$item['column']];
                } 
            }

            $param['detail_no'] = $this->last_detail_no() != null ? $this->last_detail_no()->detail_no + 1 : 0;
            $param['is_delete'] = 0;
            $param['created_user'] = $userCode;
            $param['updated_user'] = $userCode;
            $param['company_id'] = $company_id;

            //新規作成
            DB::table($this->table)
                ->insert($param);
        }else{
            foreach($define as $item)
            {
                if(isset($dataArray[$item['column']]))
                {
                    $param[$item['column']] = $dataArray[$item['column']];
                }
                else
                {
                    $param[$item['column']] = $record[$item['column']];
                }
            }
            //更新
            DB::table($this->table)
                ->where($primaryKey, $id)
                ->update($param);
        }
        return true;
    }

    /**
     * 変更可能なマスタデータの定義
     * type = value >> そのまま表示
     * type = class >> classesからdisplayNameを取得
     */
    public function getMasterDataDefine()
    {
        return [
            0 => [
                'id' => 0,
                'column' => 'office_code',
                'classes' => null,
                'displayName' => '事業所コード',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'office_name',
                'classes' => null,
                'displayName' => '事業所名称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'office_short_name',
                'classes' => null,
                'displayName' => '事業所略称',
                'type' => 'value',
                'required' => true,
            ],
            3 => [
                'id' => 3,
                'column' => 'paid_take_unit_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '日単位',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '半日単位',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '時間単位',
                    ],
                ],
                'displayName' => '有給取得単位区分',
                'type' => 'class',
                'required' => true,
            ],
            4 => [
                'id' => 4,
                'column' => 'over_time_calc_class',
                'classes' => [
                    [
                        'value' => '0',
                        'displayName' => '法定外時間外のみを計上',
                    ],
                    [
                        'value' => '1',
                        'displayName' => '法定内時間外も計上',
                    ],
                ],
                'displayName' => '時間外時間計上区分',
                'type' => 'class',
                'required' => true,
            ],
            5 => [
                'id' => 5,
                'column' => 'valid_date_start',
                'classes' => null,
                'displayName' => '有効年月日開始',
                'type' => 'date',
                'required' => false,
            ],
            6 => [
                'id' => 6,
                'column' => 'valid_date_end',
                'classes' => null,
                'displayName' => '有効年月日終了',
                'type' => 'date',
                'required' => false,
            ],
        ];
    }

    public function getData()
    {
        $m004Officedata = DB::table($this->table)->get();

        return $m004Officedata;
    }
    public function dept()
    {
        return $this->hasMany('App\Models\m005_dept', 'office_id');
    }
    public function information()
    {
        return $this->hasMany('App\Models\m002_information', 'display_office_id');
    }
    public function work_zone()
    {
        return $this->hasMany('App\Models\m023_work_zone', 'office_id');
    }
    /**
     * IDから事業所名取得
     * Deleteフラグや期限を見ない
     */
    public function getName($office_id)
    {
        $office_name = DB::table($this->table)
            ->select(
                'office_name'
            )
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->first();
        return $office_name;
    }
    public function getShortName($office_id)
    {
        $office_short_name = DB::table($this->table)
            ->select(
                'office_short_name'
            )
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->first();
        return $office_short_name;
    }
    /**
     * 会社IDから,事業所名一覧を取得
     */
    public function getOfficeList($company_id)
    {
        $office_data = DB::table($this->table)
            ->select('office_id', 'office_name')
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->get();
        return $office_data;
    }

    /**
     * 事業所IDを取得
     */
    public function checkId($office_id,$company_id,$today_serial)
    {
        return DB::table($this->table)
            ->select('office_id')
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('company_id', $company_id)
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 事業所コードを取得(csv確認用)
     */
    public function checkCode($office_code,$company_id,$today_serial)
    {
        return DB::table($this->table)
            ->select('office_code')
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('office_code', $office_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 会社IDから,事業所一覧を取得
     */
    public function getOfficeListWithTerm($company_id, $today_serial)
    {
        $office_data = DB::table($this->table)
            ->select('office_id', 'office_name')
            ->where('company_id', $company_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();
        return $office_data;
    }

    /**
     * 事業所コードを取得
     */
    public function getCode($office_id)
    {
        return DB::table($this->table)
            ->select('office_code')
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 事業所コードから事務所IDを取得
     */
    public function getIDByCode($office_code, $company_id)
    {
        return DB::table($this->table)
            ->select('office_id')
            ->where('office_code', $office_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->first();
    }
    /**
     * 初期事業所作成
     *
     * @return void
     */
    public function createDefaultOffice($company_id, $office_name)
    {
        return DB::table($this->table)
            ->insertGetId([
                'company_id' => $company_id,
                'office_name' => $office_name,
                'office_code' => '1',
                'paid_take_unit_class' => 1,
                'over_time_calc_class' => 1,
                'valid_date_start' => 0,
                'valid_date_end' => 2958465,
            ]
        );
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
}
