<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m016_close_date extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm016_close_date';
    // 更新可能な項目の設定
    protected $fillable = [
        'close_date',
        'close_date_name',
        'display_switch_date',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    // primary keyの変更
    protected $primaryKey = "close_date_id";
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
                'column' => 'close_date',
                'classes' => null,
                'displayName' => '締日',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'close_date_name',
                'classes' => null,
                'displayName' => '締日名称',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'display_switch_date',
                'classes' => null,
                'displayName' => '表示切替日',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }
    public function getCloseDates($close_date_id)
    {
        $closeDateInfo = DB::table($this->table)->select('close_date', 'close_date_name')->where('close_date_id', $close_date_id)->first();
        return $closeDateInfo;
    }

    /**
     * 名前一覧を取得
     * Key；締め日区分ID　Value：締め日区分名
     */
    public function getNameList()
    {
        $nameList = DB::table($this->table)
            ->select('close_date_id', 'close_date_name')
            ->where('is_delete', 0)
            ->get();
        
        $retArray = array();
        foreach($nameList as $data)
        {
            $retArray += array($data->close_date_id => $data->close_date_name);
        }
        return $retArray;
    }
    public function getName($close_date_id)
    {
        $close_date_name = DB::table($this->table)
            ->select(
                'close_date_name'
            )
            ->where('close_date_id', $close_date_id)
            ->where('is_delete', 0)
            ->first();
        return $close_date_name;
    }

    /**
     * 締日IDを取得
     */
    public function checkId($close_date_id)
    {
        return DB::table($this->table)
            ->select('close_date_id')
            ->where('close_date_id', $close_date_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 締め日一覧を取得
     */
    public function getCloseDateList()
    {
        $nameList = DB::table($this->table)
            ->select('close_date_id', 'close_date_name', 'close_date', 'display_switch_date', 'detail_no')
            ->where('is_delete', 0)
            ->get();
        return $nameList;
    }
}
