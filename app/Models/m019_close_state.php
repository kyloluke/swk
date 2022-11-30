<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m019_close_state extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm019_close_state';
    // 更新可能な項目の設定
    protected $fillable = [
        'close_state_class',
        'close_state_name',
        'close_state_short_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "close_state_id";
    public function getData()
    {
        $m019CloseState = DB::table($this->table)->get();

        return $m019CloseState;
    }
}
