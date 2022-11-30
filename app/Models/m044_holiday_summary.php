<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m044_holiday_summary extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm044_holiday_summary';
    // 更新可能な項目の設定
    protected $fillable = [
        'holiday_id',
        'unemployed_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "holiday_summary_id";


    /**
     * データを取得
     */
    public function getData()
    {
        return DB::table($this->table)
            ->select('holiday_summary_id','holiday_id', 'unemployed_id')
            ->where('is_delete', 0)
            ->get();
    }

}
