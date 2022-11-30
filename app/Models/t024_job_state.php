<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Http\AppLibs\LogFunctions as Log;
use Carbon\Carbon;

class t024_job_state extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 't024_job_state';
    // 更新可能な項目の設定
    protected $fillable = [
        'job_id',
        'job_name',
        'admin_id',
        'employee_id',
        'state', // 0：初期状態、1：開始、2：終了、9：異常終了
        'progress',
        'checked_at',
        'detail_no',
        'is_delete',
        'created_user',
        'updated_user',
    ];
    protected $primaryKey = "job_state_id";
    
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin', 'admin_id');
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\m007_employee', 'employee_id');
    }
    /**
     * Jobを作成してIDを取得(Admin)
     */
    public function createJobAdmin($job_name, $admin_id)
    {
        $id = DB::table($this->table)
            ->insertGetId([
                'job_id' => Uuid::uuid4()->toString(),
                'job_name' => $job_name,
                'admin_id' => $admin_id,
                'state' => 0,
            ]);
        $job = DB::table($this->table)
            ->select('job_id')
            ->where('job_state_id', $id)
            ->first();
        return $job == null ? null : $job->job_id;
    }
    /**
     * Jobを作成してIDを取得(Employee)
     */
    public function createJobEmployee($job_name, $employee_id)
    {
        $id = DB::table($this->table)
            ->insertGetId([
                'job_id' => Uuid::uuid4()->toString(),
                'job_name' => $job_name,
                'employee_id' => $employee_id,
                'state' => 0,
            ]);
        $job = DB::table($this->table)
            ->select('job_id')
            ->where('job_state_id', $id)
            ->first();
        return $job == null ? null : $job->job_id;
    }
    /**
     * Jobに開始フラグを登録
     */
    public function startJob($job_id)
    {
        return DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['state' => 1]);
    }
    /**
     * Jobに正常終了フラグを登録
     */
    public function finishJob($job_id)
    {
        return DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['state' => 2]);
    }
    /**
     * Jobに正常終了フラグを登録
     */
    public function errorJob($job_id)
    {
        return DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['state' => 9]);
    }
    /**
     * Jobにキャンセルフラグを登録
     */
    public function cancelJob($job_id)
    {
        return DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['state' => 3]);
    }
    /**
     * 追加で何か変更するとき
     */
    public function additionalStateChangeJob($job_id, $state)
    {
        return DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['state' => $state]);
    }
    /**
     * Jobの状態を取得
     */
    public function getJobState($job_id)
    {
        $state = DB::table($this->table)
            ->select('job_id', 'state') // 二つ以上のカラムを抽出必要、じゃないと、エラーが出る
            ->where('job_id', $job_id)
            ->first();

        if($state !== null)
        {
            return $state->state;
        }
        else
        {
            return null;
        }
    }
    /**
     * Jobの状態をchecked_at付きで取得
     */
    public function getJobStateWithCheckedAt($job_id)
    {
        $state = DB::table($this->table)
            ->select('state', 'checked_at')
            ->where('job_id', $job_id)
            ->first();
        
        if($state != null)
        {
            return [
                'state' => $state->state,
                'checked_at' => $state->checked_at,
            ];
        }
        else
        {
            return null;
        }
    }
    /**
     * Jobの進捗を登録
     */
    public function setProgress($job_id, $progress)
    {
        return DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['progress' => $progress]);
    }
    /**
     * Jobの状態と進捗を取得
     */
    public function getJobProgress($job_id)
    {
        $state = DB::table($this->table)
            ->select('state', 'progress')
            ->where('job_id', $job_id)
            ->first();
        //checked_atを更新
        DB::table($this->table)
            ->where('job_id', $job_id)
            ->update(['checked_at' => Carbon::now()]);
        
        if($state != null)
        {
            return [
                'state' => $state->state,
                'progress' => $state->progress,
            ];
        }
        else
        {
            return null;
        }
    }
    /**
     * Jobに対して許可があるかどうか
     */
    public function isPermited($job_id, $id)
    {
        $state = DB::table($this->table)
            ->select('admin_id', 'employee_id')
            ->where('job_id', $job_id)
            ->first();
        
        if($state != null)
        {
            if($state->admin_id != 0)
            {
                return $id == $state->admin_id;
            }
            else if($state->employee_id != 0)
            {
                return $id == $state->employee_id;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
