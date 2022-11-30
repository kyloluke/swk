<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\m007_employee;
use App\Models\m016_close_date;
use App\Models\m031_unemployed;
use App\Models\m044_holiday_summary;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\AggregateFunctions;
use App\Http\AppLibs\LogFunctions as Log;
use Illuminate\Support\Facades\DB;
class AggregateAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;
    protected $target_term;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($target_term)
    {
        $this->target_term = $target_term;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $m007_employee = new m007_employee();
        $cf = new CommonFunctions();
        $aggregate_func = new AggregateFunctions();
        $model_m016_close_date = new m016_close_date();

        $target_term = $this->target_term;
        //会社ID
        $company_id = session()->get('employee')->company_id;
        $employee_array = $m007_employee->getAllEmployeeBelongCompany($company_id);

        foreach($employee_array as $employee)
        {
            //締め区分を取得
            $close_date_id = m007_employee::find($employee->employee_id)->close_date_id;
            $close_date_info = $model_m016_close_date->getCloseDates($close_date_id);
            $close_date = $close_date_info->close_date;
            //締め日を取得
            $close_term = $cf->getCloseTerm($target_term, $close_date);
            $target_start_serial = $close_term['start_sereial'];
            $target_end_serial = $close_term['end_sereial'];

            $aggregate_func->aggregateAttendance($employee, $target_start_serial, $target_end_serial, $target_term);
        }
    }
}
