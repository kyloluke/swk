<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m028_web_punch_clock_deviation_time extends MultiTennantModel
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm028_web_punch_clock_deviation_time';
    // 更新可能な項目の設定
    protected $fillable = [
        'clocking_in_out_id',
        'allow_before_start_time',
        'allow_after_end_time',
        'company_id',
        'is_invalid',
        'origin_id',
        'detail_no',
        'is_delete',
        'field_work_distinguish',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "web_punch_clock_deviation_time_id";
    public function clocking_in_out()
    {
        return $this->belongsTo('App\Models\m025_clocking_in_out', 'clocking_in_out_id');
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
                'column' => 'clocking_in_out_id',
                'classes' => [
                    [
                        'value' => '1',
                        'displayName' => '出勤',
                    ],
                    [
                        'value' => '2',
                        'displayName' => '退勤',
                    ],
                    [
                        'value' => '3',
                        'displayName' => '外出',
                    ],
                    [
                        'value' => '4',
                        'displayName' => '戻り',
                    ],
                ],
                'displayName' => '出退勤種別',
                'type' => 'class',
                'required' => true,
            ],
            1 => [
                'id' => 1,
                'column' => 'allow_before_start_time',
                'classes' => null,
                'displayName' => '乖離許容時間(始終業前)',
                'type' => 'value',
                'required' => true,
            ],
            2 => [
                'id' => 2,
                'column' => 'allow_after_end_time',
                'classes' => null,
                'displayName' => '乖離許容時間(始終業後)',
                'type' => 'value',
                'required' => true,
            ],
        ];
    }

    public function getAllowTime($web_punch_clock_deviation_time_id)
    {
        return DB::table($this->table)
        ->select('allow_before_start_time','allow_after_end_time')
        ->where('web_punch_clock_deviation_time_id', $web_punch_clock_deviation_time_id)
        ->where('is_delete', 0)
        ->first();
    }
}
