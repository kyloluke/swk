<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class m042_operation_name extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm042_operation_name';
    // 更新可能な項目の設定
    protected $fillable = [
        'operation_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "operation_id";
}
