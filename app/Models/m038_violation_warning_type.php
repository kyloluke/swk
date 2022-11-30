<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m038_violation_warning_type extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm038_violation_warning_type';
    // 更新可能な項目の設定
    protected $fillable = [
        'violation_warning_type_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "violation_warning_type_id";
    public function getData()
    {
        $m038ViolationWarningType = DB::table($this->table)->get();

        return $m038ViolationWarningType;
    }
}
