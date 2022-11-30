<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m010_message extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm010_message';
    // 更新可能な項目の設定
    protected $fillable = [
        'messasge_class',
        'message_text',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "message_id";
    public function getData()
    {
        $m010Message = DB::table($this->table)->get();

        return $m010Message;
    }
}
