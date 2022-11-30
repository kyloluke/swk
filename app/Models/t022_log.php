<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class t022_log extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't022_log';
    // 更新可能な項目の設定
    protected $fillable = [
        'window_id',
        'operation_id',
        'log_class', // 通常：1 警告：2 エラー：3
        'log_text',
        'login_employee_id',
        'operation_target_id',
        'log_datetime',
        'company_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user',
    ];
    protected $primaryKey = "log_id";
    
    public function window()
    {
        return $this->belongsTo('App\Models\m041_window_name', 'window_id');
    }
    public function operation()
    {
        return $this->belongsTo('App\Models\m042_operation_name', 'operation_id');
    }
    public function operation_employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'login_employee_id');
    }
    public function opereation_target_employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'operation_target_id');
    }
    /**
     * ログ挿入実行
     */
    public function insertLog($window_id, $operation_id, $log_text, $operation_target_id, $log_class)
    {
        $operation_employee_id = 0;
        //ログイン中の社員情報取得
        if(Auth::check())
        {
            $operation_employee_id = Auth::id();
        }
        //null回避
        if($window_id == null)
        {
            $window_id = 0;
        }
        if($operation_id == null)
        {
            $operation_id = 0;
        }
        if($log_text == null)
        {
            $log_text = "";
        }
        if($operation_target_id == null)
        {
            $operation_target_id = 0;
        }
        //ログ時間
        $log_datetime = date_create()->modify('+9 hours')->format('Y-m-d H:i:s');

        DB::table($this->table)
            ->insert([
                'window_id' => $window_id,
                'operation_id' => $operation_id,
                'log_class' => $log_class,
                'log_text' => $log_text,
                'login_employee_id' => $operation_employee_id,
                'operation_target_id' => $operation_target_id,
                'log_datetime' => $log_datetime,
                'company_id' => 1,
        ]);
    }
}
