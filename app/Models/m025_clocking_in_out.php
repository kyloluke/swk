<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m025_clocking_in_out extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm025_clocking_in_out';
    // 更新可能な項目の設定
    protected $fillable = [
        'clocking_in_out_name',
        'clocking_in_out_short_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "clocking_in_out_id";
    public function web_punch_clock_deviation_time()
    {
        return $this->hasMany('App\Models\m028_web_punch_clock_deviation_time', 'web_punch_clock_deviation_time_id');
    }
    public function getData()
    {
        $m025ClockingInOut = DB::table($this->table)->get();

        return $m025ClockingInOut;
    }
}
