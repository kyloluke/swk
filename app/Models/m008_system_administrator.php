<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m008_system_administrator extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm008_system_administrator';
    // 更新可能な項目の設定
    protected $fillable = [
        'system_administrator_code',
        'system_administrator_name',
        'login_password',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "system_administrator_id";
    public function getData()
    {
        $m008SystemAdministrator = DB::table($this->table)->get();

        return $m008SystemAdministrator;
    }
}
