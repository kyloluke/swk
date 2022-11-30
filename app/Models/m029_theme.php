<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m029_theme extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm029_theme';
    // 更新可能な項目の設定
    protected $fillable = [
        'theme',
        'company_id',
        'is_invalid',
        'origin_theme_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "theme_id";
    public function getData()
    {
        $m029Theme = DB::table($this->table)
            ->where('is_delete', 0)
            ->get();

        return $m029Theme;
    }
}
