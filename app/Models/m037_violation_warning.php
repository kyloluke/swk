<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m037_violation_warning extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm037_violation_warning';
    // 更新可能な項目の設定
    protected $fillable = [
        'violation_warning_name',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "violation_warning_id";
    public function getData()
    {
        $m037ViolationWarning = DB::table($this->table)->get();

        return $m037ViolationWarning;
    }
}
