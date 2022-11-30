<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m034_36agreement extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm034_36agreement';
    // 更新可能な項目の設定
    protected $fillable = [
        'thirtysix_agreement_apply_id',
        'thirtysix_agreement_special_provisions_max_count',
        'valid_date_start',
        'valid_date_end',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "thirtysix_agreement_id";
    public function thirtysix_agreement_max_time()
    {
        return $this->hasMany('App\Models\m036_36agreement_max_time', 'thirtysix_agreement_id');
    }
    public function getData()
    {
        $m03436agreement = DB::table($this->table)->get();

        return $m03436agreement;
    }

    /**
     * 36協定IDを取得
     */
    public function getThirtysixAgreementData($thirtysix_agreement_apply_id,$firstday_of_month)
    {
        return DB::table($this->table)
            ->select('thirtysix_agreement_id', 'thirtysix_agreement_special_provisions_max_count')
            ->where('thirtysix_agreement_apply_id', $thirtysix_agreement_apply_id)
            ->where('valid_date_start', '<=', $firstday_of_month)
            ->where('valid_date_end', '>=', $firstday_of_month)
            ->where('is_delete', 0)
            ->first();
    }
}
