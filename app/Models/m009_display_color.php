<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m009_display_color extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm009_display_color';
    // 更新可能な項目の設定
    protected $fillable = [
        'display_color_name',
        'back_color_code',
        'fore_color_code',
        'borderline_color_code',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "display_color_id";
    public function getData()
    {
        $m009DisplayColor = DB::table($this->table)->get();

        return $m009DisplayColor;
    }
}
