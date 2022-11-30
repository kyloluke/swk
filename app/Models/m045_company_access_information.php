<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class m045_company_access_information extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm045_company_access_information';
    // 更新可能な項目の設定
    protected $fillable = [
        'company_id',
        'access_uri',
        'top_view_class',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user'
    ];
    protected $primaryKey = "company_access_information_id";

}
