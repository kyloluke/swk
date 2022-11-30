<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class m041_window_name extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm041_window_name';
    // 更新可能な項目の設定
    protected $fillable = [
        'window_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "window_id";
}
