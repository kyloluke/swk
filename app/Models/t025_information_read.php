<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class t025_information_read extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't025_information_read';
    // 更新可能な項目の設定
    protected $fillable = [
        'information_id',
        'employee_id',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user',
    ];
    protected $primaryKey = "information_read_id";
    
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin', 'admin_id');
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }


    /**
     * 既読情報取得
     */
    public function getreaded($employee_id, $information_id)
    {
        return DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('information_id', $information_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * 既読登録
     */
    public function readInformation($employee_id, $information_id, $employee_code){
        //既に登録されていれば何もしない
        if($this->getreaded($employee_id, $information_id) != null)
        {
            return true;
        }
        else
        {
            return DB::table($this->table)->insert([
                'employee_id' => $employee_id, 
                'information_id' => $information_id, 
                'created_user' => $employee_code, 
                'updated_user' => $employee_code]
            );
        }
    }
    /**
     * 未読登録
     */
    public function unreadInformation($employee_id, $information_id, $employee_code){
        $target = $this->getreaded($employee_id, $information_id);
        if($target == null)
        {
            //既読情報見つからなければ何もしない
            return true;
        }
        else
        {
            return DB::table($this->table)
                ->where('information_read_id', $target->information_read_id)
                ->update([
                    'employee_id' => $employee_id, 
                    'information_id' => $information_id, 
                    'is_delete' => 1,
                    'updated_user' => $employee_code,
                    'updated_at' => Carbon::now(),
                ]);
        }
    }
}
