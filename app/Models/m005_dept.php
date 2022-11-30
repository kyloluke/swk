<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m005_dept extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm005_dept';
    // 更新可能な項目の設定
    protected $fillable = [
        'office_id',
        'dept_code',
        'belonging_dept_id',
        'dept_name',
        'dept_short_name',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    // primary keyの変更
    protected $primaryKey = "dept_id";

    /**
     * 会社IDから一覧を取得
     */
    public function getAllData($company_id)
    {
        return DB::table($this->table)
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
        $dept_array = [];
        $dept_list = $this->getAllData(1);
        $dept_array[] = [
            'value' => '0',
            'displayName' => '全部署',
        ];
        foreach ($dept_list as $dept_info) {
            $dept_array[] = [
                'value' => strval($dept_info->dept_id),
                'displayName' => $dept_info->dept_name,
            ];
        }

        return [
            0 => [
                'id' => 0,
                'column' => 'dept_code',
                'classes' => null,
                'displayName' => '部署コード',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'belonging_dept_id',
                'classes' => $dept_array,
                'displayName' => '所属部署ID',
                'type' => 'class',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'dept_name',
                'classes' => null,
                'displayName' => '部署名称',
                'type' => 'value',
                'required' => true,
            ],
            3 => [
                'id' => 3,
                'column' => 'dept_short_name',
                'classes' => null,
                'displayName' => '部署略称',
                'type' => 'value',
                'required' => true,
            ],
            4 => [
                'id' => 4,
                'column' => 'valid_date_start',
                'classes' => null,
                'displayName' => '有効年月日開始',
                'type' => 'date',
                'required' => false,
            ],
            5 => [
                'id' => 5,
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
        $m005Deptdata = DB::table($this->table)->get();

        return $m005Deptdata;
    }
    public function office()
    {
        return $this->belongsTo('App\Models\m004_office', 'office_id');
    }
    public function belonging_dept()
    {
        return $this->hasMany('\App\Models\m005_dept', 'belonging_dept_id');
    }
    public function employee()
    {
        return $this->hasMany('\App\Models\m007_employee', 'dept_id');
    }
    public function information()
    {
        return $this->hasMany('App\Models\m002_information', 'display_dept_id');
    }
    /**
     * 所属idから所属情報を取得
     */
    public function getDeptInfo($dept_id)
    {
        $m005Deptdata = DB::table($this->table)
            ->select(
                'office_id',
                'dept_code',
                'dept_name',
                'dept_short_name',
                'belonging_dept_id',
                'valid_date_start',
                'valid_date_end',
                'detail_no',
                'is_delete',
            )
            ->where('dept_id', $dept_id)
            ->where('is_delete', 0)
            ->first();
        return $m005Deptdata;
    }
    /**
     * IDから事業所名取得
     * Deleteフラグや期限を見ない
     */
    public function getName($dept_id)
    {
        $dept_name = DB::table($this->table)
            ->select(
                'dept_name'
            )
            ->where('dept_id', $dept_id)
            ->where('is_delete', 0)
            ->first();
        return $dept_name;
    }
    public function getShortName($dept_id)
    {
        $dept_short_name = DB::table($this->table)
            ->select(
                'dept_short_name'
            )
            ->where('dept_id', $dept_id)
            ->where('is_delete', 0)
            ->first();
        return $dept_short_name;
    }
    public function getDeptName($dept_id)
    {
        $m005Deptdata = DB::table($this->table)
            ->select(
                'dept_name',
                'belonging_dept_id'
            )
            ->where('dept_id', $dept_id)
            ->where('is_delete', 0)
            ->first();
        return $m005Deptdata;
    }
    /**
     * 部署ツリーを配列で取得
     * 親→子
     * の順で配列として取得される
     */
    public function getNameTree($start_dept_id)
    {
        if($start_dept_id == 0)
        {
            return "";
        }
        $dept_id = $start_dept_id;
        $dept_names = [];
        while(true)
        {
            $retDept = $this->getDeptName($dept_id);
            array_unshift($dept_names, $retDept->dept_name);
            if($retDept->belonging_dept_id ===0)
            {
                break;
            }
            $dept_id = $retDept->belonging_dept_id;
        }
        return $dept_names;
    }
    /**
     * 部署ID,部署名一覧を取得
     */
    public function getDepartmentList()
    {
        $dept_data = DB::table($this->table)
            ->select('dept_id', 'dept_name')
            ->where('is_delete', 0)
            ->get();
        return $dept_data;
    }

    /**
     * 部署IDを取得
     */
    public function checkId($dept_id,$today_serial)
    {
        return DB::table($this->table)
            ->select('dept_id')
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('dept_id', $dept_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 部署コードを取得(csv確認用) 会社ID毎のユニークな値のためm004_officeとjoin
     */
    public function checkCode($dept_code,$company_id,$today_serial)
    {
        return $this
        ->where('valid_date_start', '<=', $today_serial)
        ->where('valid_date_end', '>=', $today_serial)
        ->where('dept_code', $dept_code)
        ->where('is_delete', 0)
        ->get()
        ->filter(function ($dept) use ($company_id) {
            return $dept->office->company_id == $company_id;
        })
        ->first();
    }

    /**
     * 部署一覧を取得
     */
    public function getDeptList($today_serial)
    {
        $dept_data = DB::table($this->table)
            ->select('dept_id', 'dept_name', 'office_id')
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();
        return $dept_data;
    }

    /**
     * 部署コードを取得
     */
    public function getCode($dept_id)
    {
        return DB::table($this->table)
            ->select('dept_code')
            ->where('dept_id', $dept_id)
            ->where('is_delete', 0)
            ->first();
    }
    
    /**
     * 部署コードから部署IDを取得 会社ID毎のユニークな値のため
     */
    public function getIDByCode($dept_code,$company_id)
    {
        return $this
            ->where('dept_code', $dept_code)
            ->where('is_delete', 0)
            ->get()
            ->filter(function ($dept) use ($company_id) {
                return $dept->office->company_id == $company_id;
            })
            ->first();
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
