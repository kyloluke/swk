<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\m007_employee;
use App\Models\t024_job_state;
use App\Http\AppLibs\LogFunctions as Log;

class ResetPasswordAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    #最大試行回数
    public $tries = 3;
    public $timeout = 1800;

    private $job_state_id;
    private $company_id;
    private $employee_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_state_id, $company_id, $employee_id)
    {
        $this->job_state_id = $job_state_id;
        $this->company_id = $company_id;
        $this->employee_id = $employee_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');
        $m007_employee = new m007_employee();
        $t024_job_state = new t024_job_state();

        //会社の全社員を取得、ID=1-20のオペレーターを除く
        $employee_array = $m007_employee->getAllEmployeeBelongCompanyExceptOperator($this->company_id);
        //job_stateを開始に変更
        $t024_job_state->startJob($this->job_state_id);
        $state = 1;
        try {
            foreach ($employee_array as $employee) {
                //対象社員ID
                $employee_id = $employee->employee_id;
                //対象社員コード
                $employee_code = $employee->employee_code;
                //ゼロパディングを行った6桁の社員コードが、パスワードになります
                $password = str_pad($employee_code, 6, '0', STR_PAD_LEFT); //左0埋め社員番号6桁
                //初期化実施
                $m007_employee->setPassword($employee_id, $password);
                //キャンセルフラグを確認
                $state = $t024_job_state->getJobState($this->job_state_id);
                if ($state == 3) {
                    break;
                }
            }
        }catch(\Exception $e) {
            //job_stateを異常終了に変更
            Log::error(2,2,__CLASS__.'@'. __METHOD__.'(行数:'.$e->getLine().')'.$e->getMessage(), $this->employee_id);
            $t024_job_state->errorJob($this->job_state_id);
        }
        Log::info(0, 999, '全社員のパスワードを初期化完了しました('.now()->toDateTimeString().')', 0);
        //job_stateを終了に変更
        if ($state != 3) {
            $t024_job_state->finishJob($this->job_state_id);
        }
        
    }
}
