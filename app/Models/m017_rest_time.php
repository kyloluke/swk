<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m017_rest_time extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm017_rest_time';
    // 更新可能な項目の設定
    protected $fillable = [
        'rest_time_name',
        'work_time_from',
        'work_time_to',
        'rest_time',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "rest_time_id";
    public function getData()
    {
        $m017RestTime = DB::table($this->table)->get();

        return $m017RestTime;
    }
}
