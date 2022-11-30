<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\t024_job_state;
use App\Http\AppLibs\CommonFunctions;

class GeneralSearchConvert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;
    protected $targetList;
    protected $job_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targetList, $job_id)
    {
        $this->targetList = $targetList;
        $this->job_id = $job_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //メモリ上限を512MBに
        ini_set("memory_limit", "512M");

        $cf = new CommonFunctions();
        $t024_job_state = new t024_job_state();

        $job_id = $this->job_id;
        $target_list = $this->targetList;
        
        //JobIDからディレクトリを特定
        $filePath = $cf->getFilePath(1, $job_id);
        //ファイル読み込み
        $contents = Storage::disk('local')->get($filePath);
        //JSON文字列をCSVに変換
        $contents_json = json_decode($contents, true);
        
        //targetListをdetail_no順にソート
        $list_size = 0;
        foreach($target_list as $kind_list)
        {
            $list_size += count($kind_list['detail_no']);
        }
        //配列のdeteil_noを順に探して昇順に並べる
        $detail_numbered_list = [];
        for($i = 0; $i < $list_size; $i++)
        {
            foreach($target_list as $kind_list)
            {
                for($j = 0; $j < count($kind_list['detail_no']); $j++)
                {
                    if($i == $kind_list['detail_no'][$j])
                    {
                        $detail_numbered_list[] = [
                            'kind' => $kind_list['kind'],
                            'columns' =>  $kind_list['columns'][$j],
                            'detail_no'=> $kind_list['detail_no'][$j],
                            'name' => $kind_list['name'][$j],
                            'type' => $kind_list['type'][$j],
                        ];
                    }
                }
            }
        }
        //出力用publicファイルパス取得
        $filePath_csv = $cf->getFilePath(3, $job_id);
        $header = [];
        //ヘッダ作成
        foreach($detail_numbered_list as $value_define)
        {
            $header[] = $value_define['name'];
        }
        Storage::disk('public')->put($filePath_csv, "\xEF\xBB\xBF" . implode(",", $header));
        //$output_array[] = implode(',', $header);
        //employeeループ
        foreach($contents_json as $employee_array)
        {
            //日毎ループ
            foreach($employee_array as $line_array)
            {
                //ここで1行の表示
                $list_data = [];
                foreach($detail_numbered_list as $value_define)
                {
                    $kind = $value_define['kind'];
                    $columns = $value_define['columns'];
                    $type = $value_define['type'];

                    $value = $line_array[$kind][$columns];
                    //$value = $line_array->$kind->$columns;
                    //値コンバート
                    if($type == 'time')
                    {
                        $value = $cf->serialToTimeStr($value);
                    }
                    if($type == 'hours')
                    {
                        $value = $cf->serialToHoursStr($value);
                    }
                    if($type == 'totalTimeSerial')
                    {
                        $value = $cf->serialToTimeStr($value['totalTimeSerial']);
                    }
                    if($type == 'totalCount')
                    {
                        $value = $value['totalCount'];
                    }
                    if($type == 'date')
                    {
                        $value = $cf->serialToDateStr($value, 'Y/m/d');
                    }
                    $list_data[] = print_r($value, true);
                }
                Storage::disk('public')->append($filePath_csv, implode(",", $list_data));
            }
        }
        $t024_job_state->additionalStateChangeJob($job_id, 5);
    }
}