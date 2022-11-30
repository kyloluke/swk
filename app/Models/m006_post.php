<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m006_post extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm006_post';
    // 更新可能な項目の設定
    protected $fillable = [
        'company_id',
        'post_code',
        'post_name',
        'post_short_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    // primary keyの変更
    protected $primaryKey = "post_id";

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
                'column' => 'post_code',
                'classes' => null,
                'displayName' => '部署コード',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'post_name',
                'classes' => null,
                'displayName' => '部署名称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'post_short_name',
                'classes' => null,
                'displayName' => '部署略称',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
    public function getData()
    {
        $m006Postdata = DB::table($this->table)->get();

        return $m006Postdata;
    }
    public function getName($post_id)
    {
        $post_name = DB::table($this->table)
            ->select(
                'post_name'
            )
            ->where('post_id', $post_id)
            ->where('is_delete', 0)
            ->first();
        return $post_name;
    }
    public function getShortName($post_id)
    {
        $post_short_name = DB::table($this->table)
            ->select(
                'post_short_name'
            )
            ->where('post_id', $post_id)
            ->where('is_delete', 0)
            ->first();
        return $post_short_name;
    }
    /**
     * 指定したIDの役職名を取得
     * 役職名が取得できなかった場合、空文字を返す
     */
    public function getPostName($post_id)
    {
        $post_name = DB::table($this->table)
            ->select(
                'post_name'
            )
            ->where('post_id', $post_id)
            ->where('is_delete', 0)
            ->first();
        if(empty($post_name))
        {
            return '';
        }
        else
        {
            return $post_name->post_name;
        }
    }

    /**
     * 指定したIDの役職名を取得
     * 役職名が取得できなかった場合、なしを返す
     */
    public function getPostNameWithNashi($post_id)
    {
        $post_name = DB::table($this->table)
            ->select(
                'post_name'
            )
            ->where('post_id', $post_id)
            ->where('is_delete', 0)
            ->first();
        if(empty($post_name))
        {
            return 'なし';
        }
        else
        {
            return $post_name->post_name;
        }
    }

    /**
     * 役職IDを取得
     */
    public function checkId($post_id)
    {
        return DB::table($this->table)
            ->select('post_id')
            ->where('post_id', $post_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 役職コードを取得(csv確認用)
     */
    public function checkCode($post_code, $company_id)
    {
        return DB::table($this->table)
            ->select('post_code')
            ->where('post_code', $post_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->first();
    }


    /**
     * 会社IDから,役職名一覧を取得
     */
    public function getPostList($company_id)
    {
        $post_data = DB::table($this->table)
            ->select('post_id', 'post_name')
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->get();
        return $post_data;
    }

    /**
     * 役職コードを取得
     */
    public function getCode($post_id)
    {
        return DB::table($this->table)
            ->select('post_code')
            ->where('post_id', $post_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 役職コードから役職IDを取得
     */
    public function getIDByCode($post_code, $company_id)
    {
        return DB::table($this->table)
            ->select('post_id')
            ->where('post_code', $post_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
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