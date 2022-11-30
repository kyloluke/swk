<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m026_week extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm026_week';
    // 更新可能な項目の設定
    protected $fillable = [
        'week_name',
        'week_short_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "week_id";
    public function getData()
    {
        $m026Week = DB::table($this->table)->get();

        return $m026Week;
    }
}
