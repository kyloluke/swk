<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Scopes\CompanyScope;

class MultiTennantModel extends Model
{
    use HasFactory;
    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
    
    /**
     * 変更可能なマスタデータの定義
     * type = value >> そのまま表示
     * type = class >> classesからdisplayNameを取得
     */
    public function getMasterDataDefine()
    {
        return [];
        
        //以下はm030の場合のExampleとして記述
        return [
            0 => [
                'id' => 0,
                'column' => 'work_achievement_name',
                'classes' => null,
                'displayName' => '実績名称',
                'type' => 'value',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'work_achievement_short_name',
                'classes' => null,
                'displayName' => '実績略称',
                'type' => 'value',
                'required' => false,
            ],
            2 => [
                'id' => 2,
                'column' => 'work_achievement_display_class',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '共通',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '通常出勤日',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '休日',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '休日（振替あり）',
                    ],
                    [
                        'value' => '5',
                        'displayName' => '振替休日',
                    ],
                ],
                'displayName' => '実績区分',
                'type' => 'class',
                'required' => true,
            ],
        ];
    }
    /**
     * 有効なマスタデータのみすべて取得する
     * 親マスタデータが登録されているものがある場合、親マスタは除外して返す
     */
    public function getData()
    {
        $primaryKey = $this->getKeyName();
        $allData = $this->all()->toArray();
        //取り除く親IDチェック
        $has_child = [];
        foreach($allData as $item)
        {
            if($item['origin_id'] != 0)
            {
                //取り除く親
                $has_child[] = $item['origin_id'];
            }
        }
        //再生成
        $ret_array = [];
        foreach($allData as $item)
        {
            if(!in_array($item[$primaryKey], $has_child) && $item['is_invalid'] == 0)
            {
                $ret_array[] = $item;
            }
        }
        return $ret_array;
    }
    /**
     * 無効に設定した者も含めて、マスタデータをすべて取得する
     * 親マスタデータが登録されているものがある場合、親マスタは除外して返す
     */
    public function getAllData()
    {
        $primaryKey = $this->getKeyName();
        $allData = $this->all()->toArray();
        //取り除く親IDチェック
        $has_child = [];
        foreach($allData as $item)
        {
            if($item['origin_id'] != 0)
            {
                //取り除く親
                $has_child[] = $item['origin_id'];
            }
        }
        //再生成
        $ret_array = [];
        foreach($allData as $item)
        {
            if(!in_array($item[$primaryKey], $has_child))
            {
                $ret_array[] = $item;
            }
        }
        return $ret_array;
    }
    /**
     * 指定IDを有効化する
     */
    public function enableMasterData($id)
    {
        $primaryKey = $this->getKeyName();
        //company_id=0のレコードは操作禁止
        $record = DB::table($this->table)
            ->select('company_id')
            ->where($primaryKey, $id)
            ->first();
        if($record == null || $record->company_id == 0)
        {
            return false;
        }
        
        DB::table($this->table)
            ->where($primaryKey, $id)
            ->update(
            [
                'is_invalid' => 0,
            ]
        );
        return true;
    }
    /**
     * 指定IDを無効化する
     * company_id=0のものを無効化する場合は、親マスタIDを持たせたレコードを複製してis_invalid=1を登録する
     */
    public function disableMasterData($id)
    {
        $primaryKey = $this->getKeyName();
        $record = DB::table($this->table)
            ->where($primaryKey, $id)
            ->first();
        if($record == null)
        {
            //無効なID指定の場合はfalseリターン（company_id=0,自社ID以外のものはscopeによって不可）
            return false;
        }
        if($record->company_id == 0)
        {
            //複製データがあるか確認
            $copy_record = DB::table($this->table)
                ->where('origin_id', $id)
                ->first();
            if($copy_record == null)
            {
                $define = $this->getMasterDataDefine();
                $param = [
                    'company_id' => Auth::user()->company_id,
                    'is_invalid' => 1,
                    'origin_id' => $id,
                ];
                foreach($define as $item)
                {
                    $param[$item['column']] = $record->pluck($item['column'])[0];
                }

                //複製レコード無しのため複製
                DB::table($this->table)->insert($param);
            }
            else
            {
                //複製レコードをinvalidに変更
                DB::table($this->table)
                    ->where($primaryKey, $copy_record->pluck($primaryKey)[0])
                    ->update(
                    [
                        'is_invalid' => 0,
                    ]
                );
            }
        }
        else
        {
            //自社レコードはそのまま修正
            DB::table($this->table)
                ->where($primaryKey, $id)
                ->update(
                [
                    'is_invalid' => 0,
                ]
            );
        }
        return true;
    }
    /**
     * マスタデータを変更する
     * 使用可否は変更しないが、company_id=0のものを変更するときは親マスタIDを持たせたレコードを複製して編集する
     */
    public function updateMasterData($dataArray){
        $primaryKey = $this->getKeyName();
        $define = $this->getMasterDataDefine();
        //必須項目のチェック
        if(!isset($dataArray[$primaryKey]) || !isset($dataArray["is_invalid"]))
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
        $is_invalid = $dataArray["is_invalid"];

        //該当のレコードを取得
        $record = DB::table($this->table)
            ->where($primaryKey, $id)
            ->first();
        if($record == null)
        {
            //無効なID指定の場合はfalseリターン（company_id=0,自社ID以外のものはscopeによって不可）
            return false;
        }
        $record = get_object_vars($record);
        $param = [];
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
        $param['is_invalid'] = $is_invalid ? 1 : 0;
        if($record['company_id'] == 0)
        {
            //複製データがあるか確認
            $copy_record = DB::table($this->table)
                ->where('origin_id', $id)
                ->first();
            if($copy_record == null)
            {
                $param['company_id'] = Auth::user()->company_id;
                $param['origin_id'] = $id;
                //複製レコード無しのため複製
                DB::table($this->table)->insert($param);
            }
            else
            {
                //複製レコードを更新
                DB::table($this->table)
                    ->where($primaryKey, get_object_vars($copy_record)[$primaryKey])
                    ->update($param);
            }
        }
        else
        {
            //自社レコードはそのまま更新
            DB::table($this->table)
                ->where($primaryKey, $id)
                ->update($param);
        }
        return true;
    }
}
