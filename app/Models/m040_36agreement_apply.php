<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m040_36agreement_apply extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm040_36agreement_apply';
    // 更新可能な項目の設定
    protected $fillable = [
        'thirtysix_agreement_apply_name',
        'thirtysix_agreement_apply_class',
        'company_id',
        'is_invalid',
        'origin_thirtysix_agreement_apply_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "thirtysix_agreement_apply_id";
    public function thirtysix_agreement()
    {
        return $this->hasMany('App\Models\m034_36agreement', 'thirtysix_agreement_apply_id');
    }
    public function getData()
    {
        $m04036agreementApply = DB::table($this->table)->get();

        return $m04036agreementApply;
    }

    public function getName($thirtysix_agreement_apply_id)
    {
        $thirtysix_agreement_apply_name = DB::table($this->table)
            ->select(
                'thirtysix_agreement_apply_name'
            )
            ->where('thirtysix_agreement_apply_id', $thirtysix_agreement_apply_id)
            ->first();
        return $thirtysix_agreement_apply_name;
    }

    /**
     * 36協定適用IDを取得
     */
    public function checkId($thirtysix_agreement_apply_id)
    {
        return DB::table($this->table)
            ->select('thirtysix_agreement_apply_id')
            ->where('thirtysix_agreement_apply_id', $thirtysix_agreement_apply_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 36協定適用一覧を取得
     */
    public function getThirtysixAgreementApplyList()
    {
        $thirtysix_agreement_apply_list = DB::table($this->table)
            ->select('thirtysix_agreement_apply_id', 'thirtysix_agreement_apply_name')
            ->where('is_delete', 0)
            ->get();
        return $thirtysix_agreement_apply_list;
    }
}
