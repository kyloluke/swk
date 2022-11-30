<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;

class TransferWebPunch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:transferwebpunch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer un-transfered web punch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model_t001_web_punch_clock = new t001_web_punch_clock();
        $model_t002_attendance_information = new t002_attendance_information();
        //Web打刻を転記
        $transfer_web_punch_array = $model_t001_web_punch_clock->getUnTransferData();
        foreach($transfer_web_punch_array as $web_punch)
        {
            //転送処理が必要なのは1出勤、2退勤のみ
            if($web_punch->clocking_in_out_id == 1)
            {
                $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($web_punch->employee_id, $web_punch->punch_clock_date, 1, $web_punch->punch_clock_time, $web_punch->input_class);
            }
            else if($web_punch->clocking_in_out_id == 2)
            {
                //退勤は転送済みの手入力打刻が無い場合のみ転送
                if($model_t001_web_punch_clock->getInputDataWithinData($web_punch->employee_id, $web_punch->punch_clock_date, $web_punch->clocking_in_out_id) == null){
                    $model_t002_attendance_information->updateAttendanceInformationWebPunchClockTime($web_punch->employee_id, $web_punch->punch_clock_date, 2, $web_punch->punch_clock_time, $web_punch->input_class);
                }
            }
            //転送フラグを「１：転送完了」に更新
            $model_t001_web_punch_clock->updateTransferClass($web_punch->web_punch_clock_id, 1);
        }
        return 0;
    }
}
