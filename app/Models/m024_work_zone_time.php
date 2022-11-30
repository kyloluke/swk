<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m024_work_zone_time extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm024_work_zone_time';
    // 更新可能な項目の設定
    protected $fillable = [
        'work_zone_id',
        'time_type_class',
        'start_time',
        'end_time',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "work_zone_time_id";
    public function getData()
    {
        $m024WorkZoneTime = DB::table($this->table)->get();

        return $m024WorkZoneTime;
    }

    /**
     * 勤務帯ID、時間種別区分からデータ取得
     */
    public function getStartEndTime($work_zone_id,$time_type_class)
    {
        return DB::table($this->table)
        ->select('start_time','end_time')
        ->where('work_zone_id', $work_zone_id)
        ->where('time_type_class', $time_type_class)
        ->where('is_delete', 0)
        ->first();
    }

    /**
     * 勤務帯ID、時間種別区分から勤務帯時間ID、開始時間、終了時間一覧を取得
     */
    public function getStartEndList($work_zone_id, $time_type_class)
    {
        $start_end_time = DB::table($this->table)
            ->select('work_zone_time_id', 'start_time', 'end_time')
            ->where('work_zone_id', $work_zone_id)
            ->where('time_type_class', $time_type_class)
            ->where('is_delete', 0)
            ->get();
        return $start_end_time;
    }
    /**
     * 勤務帯時間修正
     */
    public function editWorkZoneTimeInfo($work_zone_time)
    {
        //実働時間の登録
        if($work_zone_time['actual_zone_time_id'] === 0)
        {
            $max_detail_no_obj = DB::table($this->table)
                ->select('detail_no')
                ->orderBy('detail_no', 'DESC')
                ->take(1)
                ->first();
            //新規作成
            $work_zone_time_id = DB::table($this->table)
                ->insert([
                    'work_zone_id' => $work_zone_time['work_zone_id'],
                    'time_type_class' => 1,
                    'start_time' => $work_zone_time['actual_start_time'],
                    'end_time' => $work_zone_time['actual_end_time'],
                    'detail_no' => ($max_detail_no_obj->detail_no + 1),
                ]);
        }
        else
        {
            //更新
            DB::table($this->table)
                ->where('work_zone_time_id', $work_zone_time['actual_zone_time_id'])
                ->update([
                    'start_time' => $work_zone_time['actual_start_time'],
                    'end_time' => $work_zone_time['actual_end_time'],
                ]);
        }
        //休憩時間の更新
        $break_array = $work_zone_time['break_work_zone_time'];
        for($i = 0; $i < count($break_array); $i++)
        {
            if($break_array[$i]['work_zone_time_id'] === 0)
            {
                //新規登録
                //最終detail_no取得
                $max_detail_no_obj = DB::table($this->table)
                    ->select('detail_no')
                    ->orderBy('detail_no', 'DESC')
                    ->take(1)
                    ->first();
                DB::table($this->table)
                    ->insert([
                        'work_zone_id' => $work_zone_time['work_zone_id'],
                        'time_type_class' => 2,
                        'start_time' => $break_array[$i]['start_time'],
                        'end_time' => $break_array[$i]['end_time'],
                        'detail_no' => ($max_detail_no_obj->detail_no + 1),
                        'is_delete' => 0,
                    ]);
            }
            else if($break_array[$i]['work_zone_time_id'] !== -1)
            {
                if($break_array[$i]['start_time'] === null && $break_array[$i]['end_time'] === null)
                {
                    //削除
                    DB::table($this->table)
                        ->where('work_zone_time_id', $break_array[$i]['work_zone_time_id'])
                        ->update(['is_delete' => 1]);
                }
                else
                {
                    //更新
                    DB::table($this->table)
                        ->where('work_zone_time_id', $break_array[$i]['work_zone_time_id'])
                        ->update([
                            'work_zone_id' => $work_zone_time['work_zone_id'],
                            'time_type_class' => 2,
                            'start_time' => $break_array[$i]['start_time'],
                            'end_time' => $break_array[$i]['end_time'],
                            'is_delete' => 0,
                        ]);
                }
            }
            else
            {
                //-1の時は無効値として何もしない
            }
        }
        return true;
    }
    /**
     * 有効なマスタデータ一覧取得
     */
    public function getValidList()
    {
        $valid_list = DB::table($this->table)
            ->select(
                'work_zone_time_id',
                'work_zone_id',
                'time_type_class',
                'start_time',
                'end_time',
                'detail_no')
            ->where('is_delete', 0)
            ->get();
        return $valid_list;
    }
}
