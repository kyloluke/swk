<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m023_work_zone extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm023_work_zone';
    // 更新可能な項目の設定
    protected $fillable = [
        'office_id',
        'work_zone_code',
        'work_zone_name',
        'work_zone_aggrigation_class',
        'company_id',
        'is_invalid',
        'origin_work_zone_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user',
        'actual_work_time',
        'midnight_actual_work_time',
        'break_time',
        'midnight_break_time',
    ];
    protected $primaryKey = "work_zone_id";
    public function work_zone_time()
    {
        return $this->hasMany('App\Models\m024_work_zone_time', 'work_zone_id');
    }
    public function getData()
    {
        $m023WorkZone = DB::table($this->table)->get();

        return $m023WorkZone;
    }
    
    /**
     * 勤務帯IDから勤務帯名を取得
     */
    public function getNameByID($work_zone_id)
    {
        return DB::table($this->table)
            ->select('work_zone_name')
            ->where('work_zone_id', $work_zone_id)
            ->first();
    }

    /**
     * 勤務帯IDから勤務帯コードを取得
     */
    public function getCodeByID($work_zone_id)
    {
        return DB::table($this->table)
            ->select('work_zone_code')
            ->where('work_zone_id', $work_zone_id)
            ->first();
    }

    /**
     * 勤務帯コードから勤務帯IDを取得
     */
    public function getIDByCode($work_zone_code,$company_id)
    {
        return DB::table($this->table)
            ->select('work_zone_id')
            ->where('work_zone_code', $work_zone_code)
            ->whereIn('company_id', [0, $company_id])
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 勤務帯IDを取得
     */
    public function checkId($work_zone_id)
    {
        return DB::table($this->table)
            ->select('work_zone_id')
            ->where('work_zone_id', $work_zone_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 勤務帯コードを取得(csv確認用)
     */
    public function checkCode($work_zone_code, $company_id)
    {
        return DB::table($this->table)
            ->select('work_zone_code')
            ->where('work_zone_code', $work_zone_code)
            ->whereIn('company_id', [0, $company_id])
            ->where('is_delete', 0)
            ->first();
    }
    
    /**
     * 事業所IDから勤務帯ID、勤務帯名、所定労働時間、所定休憩時間を取得
     */
    public function getNameByOfficeID($office_id)
    {
        $office_data = DB::table($this->table)
            ->select('work_zone_id', 'work_zone_code', 'work_zone_name','work_zone_aggrigation_class', 'actual_work_time', 'break_time', 'midnight_actual_work_time', 'midnight_break_time')
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->get();
        return $office_data;
    }
    /**
     * 勤務帯登録
     */
    public function registerWorkZoneInfo($work_zone)
    {
        $work_zone_id = DB::table($this->table)
            ->insertGetId([
                'office_id' => $work_zone['office_id'],
                'work_zone_code' => $work_zone['work_zone_code'],
                'work_zone_name' => $work_zone['work_zone_name'],
                'actual_work_time' => $work_zone['actual_work_time'],
                'break_time' => $work_zone['break_time'],
            ]);

        DB::table($this->table)
            ->where('work_zone_id', $work_zone_id)
            ->update(['detail_no' => $work_zone_id]);

        return $work_zone_id;
    }
    /**
     * 勤務帯修正
     */
    public function editWorkZoneInfo($work_zone)
    {
        $result = 0;
        if($work_zone['work_zone_id'] == 0)
        {
            $max_detail_no_obj = DB::table($this->table)
                ->select('detail_no')
                ->orderBy('detail_no', 'DESC')
                ->take(1)
                ->first();
            //新規作成
            $result = DB::table($this->table)
                ->insertGetId([
                    'company_id' => $work_zone['company_id'],
                    'office_id' => $work_zone['office_id'],
                    'work_zone_code' => $work_zone['work_zone_code'],
                    'work_zone_name' => $work_zone['work_zone_name'],
                    'actual_work_time' => $work_zone['actual_work_time'],
                    'break_time' => $work_zone['break_time'],
                    'detail_no' => ($max_detail_no_obj->detail_no + 1),
                    'work_zone_aggrigation_class' => $work_zone['work_zone_aggrigation_class']
                ]);
        }
        else
        {
            DB::table($this->table)
                ->where('work_zone_id', $work_zone['work_zone_id'])
                ->update([
                    'office_id' => $work_zone['office_id'], //事業所id
                    'work_zone_code' => $work_zone['work_zone_code'], //勤務帯コード
                    'work_zone_name' => $work_zone['work_zone_name'], //勤務帯名称
                    'actual_work_time' => $work_zone['actual_work_time'], //所定労働時間
                    'break_time' => $work_zone['break_time'], //所定休憩時間
                    'work_zone_aggrigation_class' => $work_zone['work_zone_aggrigation_class']
                ]
            );
            $result = 0;
        }

        return $result;
    }

    /**
     * 勤務帯一覧を取得
     */
    public function getWorkZoneList()
    {
        $work_zone_list = DB::table($this->table)
            ->select('work_zone_id', 'office_id', 'work_zone_code', 'work_zone_name', 'work_zone_aggrigation_class', 'actual_work_time', 'break_time', 'midnight_actual_work_time', 'midnight_break_time', 'detail_no')
            ->where('is_delete', 0)
            ->get();
        return $work_zone_list;
    }
}
