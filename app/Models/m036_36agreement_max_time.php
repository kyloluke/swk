<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m036_36agreement_max_time extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm036_36agreement_max_time';
    // 更新可能な項目の設定
    protected $fillable = [
        'thirtysix_agreement_id',
        'thirtysix_agreement_aggregate_class_id',
        'thirtysix_agreement_aggregate_unit',
        'max_time',
        'holiday_work_aggregate_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "thirtysix_agreement_max_time_id";
    public function thirtysix_agreement_aggregate_class()
    {
        return $this->belongsTo('App\Models\m035_36agreement_aggregate_class', 'thirtysix_agreement_aggregate_class_id');
    }
    public function getData()
    {
        $m03636agreementMaxTime = DB::table($this->table)->get();

        return $m03636agreementMaxTime;
    }


    /**
     * 上限時間を取得
     */
    public function getThirtysixAgreementId($thirtysix_agreement_id,$thirtysix_agreement_aggregate_class_id)
    {
        return DB::table($this->table)
            ->select('max_time')
            ->where('thirtysix_agreement_id', $thirtysix_agreement_id)
            ->where('thirtysix_agreement_aggregate_class_id', $thirtysix_agreement_aggregate_class_id)
            ->where('is_delete', 0)
            ->first();
    }
}
