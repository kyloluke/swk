<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t024_job_state;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\GeneralSearchFunctions;
use App\Http\AppLibs\LogFunctions as Log;
use App\Models\m007_employee;
use Carbon\Carbon;
use Exception;

class GeneralSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;
    protected $targetList;
    protected $employee_list;
    protected $start_date;
    protected $end_date;
    protected $unit_type;
    protected $job_id;
    protected $seq_no;
    protected $is_csv;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($targetList, $employee_list, $start_date, $end_date, $unit_type, $job_id, $seq_no, $is_csv)
    {
        $this->targetList = $targetList;
        $this->employee_list = $employee_list;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->unit_type = $unit_type;
        $this->job_id = $job_id;
        $this->seq_no = $seq_no;
        $this->is_csv = $is_csv;
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
        $general_search_func = new GeneralSearchFunctions();
        $t002_attendance_information = new t002_attendance_information();
        $t024_job_state = new t024_job_state();

        $job_id = $this->job_id;
        $defineList = $general_search_func->defineGeneralSearchList();
        $targetList = $this->targetList;
        $employee_list = $this->employee_list;
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        $unit_type = $this->unit_type;
        $seq_no = $this->seq_no;
        $is_csv = $this->is_csv;

        //job_stateを確認
        $job_state = $t024_job_state->getJobProgress($job_id);
        if($job_state == null)
        {
            Log::info(0, 0, "汎用検索実行エラー(Jobが取得できませんでした) >> job_id = " . $job_id . ", seq_no = " . $seq_no, 999);
        }
        switch($job_state['state'])
        {
            case 0: //開始前
                //job_stateを開始に変更
                Log::info(0, 0, "汎用検索Job開始 >> job_id = " . $job_id, 999);
                $t024_job_state->startJob($job_id);
                break;
            case 1: //開始後
                //なにもしない
                Log::info(0, 0, "汎用検索実行中 >> job_id = " . $job_id . ", seq_no = " . $seq_no, 999);
                break;
            case 2: //終了
                //あり得ないはずなので何もしない
                break;
            case 3:
                //キャンセルされたJob
                //ここでログ出して終了
                Log::info(0, 0, "汎用検索実行中断済 >> job_id = " . $job_id . ", seq_no = " . $seq_no, 999);
                break;
        }
        $state = 1;
        //現在のprogressを取得
        $progress = $job_state['progress'];
        $progress_total = $unit_type == 1 ? count($employee_list) * ($end_date - $start_date + 1) : count($employee_list) * ($cf->compaireYearMonth($start_date, $end_date) + 1);
        $progress_scale = $progress_total < 5000 ? 100 : 1000;

        //データ保持用配列
        $ret = [];
        //検索実行
        //社員ループ
        foreach($employee_list as $employee_id)
        {
            //キャンセルの場合処理中断
            $state = $t024_job_state->getJobState($job_id);
            if($state == 3)
            {
                break;
            }
            $ret[$employee_id] = [];
            switch($unit_type)
            {
                case 1: // 日次集計
                    //日付ループ
                    for($date_serial = $start_date; $date_serial <= $end_date; $date_serial++)
                    {
                        $item_buff = [];
                        //t002のID特定
                        $attendance_info = $t002_attendance_information->getAttendanceInformationByDate($employee_id, $date_serial);
                        if($attendance_info == null)
                        {
                            continue;
                        }
                        $attendance_information_id = $attendance_info->attendance_information_id;

                        $ret[$employee_id][$date_serial] = [];
                        //kindループ
                        foreach($targetList as $target)
                        {
                            //対象データ取得
                            $kind = $target['kind'];
                            //バッファが無ければ箱作成
                            if(!isset($item_buff[$kind]))
                            {
                                $item_buff[$kind] = null;
                            }
                            //unitType検証
                            $unitType = $defineList[$kind]['unitType'];
                            if($unitType != 0 && $unitType != 1)
                            {
                                //0と1のみ受け付け
                                continue;
                            }
                            //データ保持用配列にkindが存在していなければ追加
                            if(!isset($ret[$employee_id][$date_serial][$kind]))
                            {
                                $ret[$employee_id][$date_serial][$kind] = [];
                            }
                            foreach($target["columns"] as $column_id)
                            {
                                //kind0or1は基本情報
                                if($kind == 0 || $kind == 1 || $kind == 7)
                                {
                                    if($item_buff[$kind] == null)
                                    {
                                        $item_buff[$kind] = $defineList[$kind]['getData']($employee_id, $date_serial);
                                    }
                                    $item = $item_buff[$kind];
                                    $column_name = $defineList[$kind]['columns'][$column_id]['column'];
                                    $column_value = $item->$column_name;
                                    if($defineList[$kind]['columns'][$column_id]['child'] != null)
                                    {
                                        $column_value = $defineList[$kind]['columns'][$column_id]['child']($column_value);
                                    }
                                    $ret[$employee_id][$date_serial][$kind][$column_id] = $column_value;

                                }
                                // 休暇
                                else if($kind == 10)
                                {
                                    if($item_buff[$kind] == null)
                                    {
                                        $item_buff[$kind] = $defineList[$kind]['getData']($attendance_information_id);
                                    }
                                    $item = $item_buff[$kind];
                                    $column_name = $defineList[$kind]['columns'][$column_id]['column'];
                                    $column_value = $item->$column_name;
                                    $ret[$employee_id][$date_serial][$kind][$column_id] = $column_value;
                                }
                                //その他は項目に応じて取得する
                                else
                                {
                                    if($item_buff[$kind] == null)
                                    {
                                        $item_buff[$kind] = $defineList[$kind]['getData']($attendance_information_id);
                                    }
                                    $item = $item_buff[$kind];
                                    $ret[$employee_id][$date_serial][$kind][$column_id] = [];
                                    if(isset($defineList[$kind]['totalCount']))
                                    {
                                        $total_count = $defineList[$kind]['totalCount']($item, $column_id);
                                        $ret[$employee_id][$date_serial][$kind][$column_id]['totalCount'] = $total_count;
                                    }
                                    if(isset($defineList[$kind]['totalTimeSerial']))
                                    {
                                        $total_time_serial = $defineList[$kind]['totalTimeSerial']($item, $column_id);
                                        $ret[$employee_id][$date_serial][$kind][$column_id]['totalTimeSerial'] = $total_time_serial;
                                    }
                                    if(isset($defineList[$kind]['indexedText']))
                                    {
                                        $ret[$employee_id][$date_serial][$kind][$column_id] = $defineList[$kind]['indexedText']($item, $column_id);
                                    }
                                    //複数カラムの合計について実装中（実装途中）
                                    if(isset($defineList[$kind]['columns'][$column_id]['column']))
                                    {
                                        if(isset($defineList[$kind]['columns'][$column_id]['column']))
                                        {
                                            //単一カラム
                                            $column_name = $defineList[$kind]['columns'][$column_id]['column'];
                                            $column_value = $item->$column_name;
                                            if($defineList[$kind]['columns'][$column_id]['child'] != null)
                                            {
                                                $column_value = $defineList[$kind]['columns'][$column_id]['child']($column_value);
                                            }
                                            $ret[$employee_id][$date_serial][$kind][$column_id] = $column_value;
                                        }
                                        else if(isset($defineList[$kind]['columns'][$column_id]['columns']))
                                        {
                                            //複数カラムの合計

                                        }
                                    }
                                }
                            }
                        }
                        //進捗進める
                        $progress++;
                        if($progress % (ceil($progress_total / $progress_scale)) == 0)
                        {
                            $t024_job_state->setProgress($job_id, $progress);
                        }
                    }
                    break;
                case 2: //月次集計
                    $t003_attendance_aggregate = new t003_attendance_aggregate();
                    //差分取得
                    $months = $cf->compaireYearMonth($start_date, $end_date);
                    //年月ループ
                    for($i = 0; $i <= $months; $i++)
                    {
                        $yearMonth = $cf->calcYearMonth($start_date, $i);
                        //対象の期間末時点の社員情報とするため、締め日基準の期間を取得
                        $targetTerm = $cf->getCloseTerm($yearMonth, m007_employee::find($employee_id)->close_date->close_date);
                        
                        //t003のID特定
                        $attendance_aggregate_info = $t003_attendance_aggregate->getAttendanceAggregateWithinTerm($employee_id, $yearMonth);
                        if($attendance_aggregate_info == null)
                        {
                            continue;
                        }
                        $attendance_aggregate_id = $attendance_aggregate_info->attendance_aggregate_id;

                        $ret[$employee_id][$yearMonth] = [];
                        //kindループ
                        foreach($targetList as $target)
                        {
                            //対象データ取得
                            $kind = $target['kind'];

                            //unitType検証
                            $unitType = $defineList[$kind]['unitType'];
                            if($unitType != 0 && $unitType != 2)
                            {
                                //0と2のみ受け付け
                                continue;
                            }
                            //データ保持用配列にkindが存在していなければ追加
                            if(!isset($ret[$employee_id][$yearMonth][$kind]))
                            {
                                $ret[$employee_id][$yearMonth][$kind] = [];
                            }
                            foreach($target["columns"] as $column_id)
                            {
                                //kind0は基本情報
                                if($kind == 0)
                                {
                                    $item = $defineList[$kind]['getData']($employee_id, $targetTerm['end_sereial']);
                                    $column_name = $defineList[$kind]['columns'][$column_id]['column'];
                                    $column_value = $item->$column_name;
                                    if($defineList[$kind]['columns'][$column_id]['child'] != null)
                                    {
                                        $column_value = $defineList[$kind]['columns'][$column_id]['child']($column_value);
                                    }
                                    $ret[$employee_id][$yearMonth][$kind][$column_id] = $column_value;
                                }
                                //kind2はt003情報
                                else if($kind == 2 || $kind == 10)
                                {
                                    $item = $defineList[$kind]['getData']($employee_id, $yearMonth);
                                    $column_name = $defineList[$kind]['columns'][$column_id]['column'];
                                    $column_value = $item->$column_name;
                                    if($defineList[$kind]['columns'][$column_id]['child'] != null)
                                    {
                                        $column_value = $defineList[$kind]['columns'][$column_id]['child']($column_value);
                                    }
                                    $ret[$employee_id][$yearMonth][$kind][$column_id] = $column_value;
                                }
                                //その他は項目に応じて取得する
                                else
                                {
                                    $item = $defineList[$kind]['getData']($attendance_aggregate_id);
                                    $ret[$employee_id][$yearMonth][$kind][$column_id] = [];
                                    if(isset($defineList[$kind]['totalCount']))
                                    {
                                        $total_count = $defineList[$kind]['totalCount']($item, $column_id);
                                        $ret[$employee_id][$yearMonth][$kind][$column_id]['totalCount'] = $total_count;
                                    }
                                    if(isset($defineList[$kind]['totalTimeSerial']))
                                    {
                                        $total_time_serial = $defineList[$kind]['totalTimeSerial']($item, $column_id);
                                        $ret[$employee_id][$yearMonth][$kind][$column_id]['totalTimeSerial'] = $total_time_serial;
                                    }
                                    //複数カラムの合計について実装中（実装途中）
                                    if(isset($defineList[$kind]['columns'][$column_id]['column']))
                                    {
                                        if(isset($defineList[$kind]['columns'][$column_id]['column']))
                                        {
                                            //単一カラム
                                            $column_name = $defineList[$kind]['columns'][$column_id]['column'];
                                            $column_value = $item->$column_name;
                                            if($defineList[$kind]['columns'][$column_id]['child'] != null)
                                            {
                                                $column_value = $defineList[$kind]['columns'][$column_id]['child']($column_value);
                                            }
                                            $ret[$employee_id][$yearMonth][$kind][$column_id] = $column_value;
                                        }
                                        else if(isset($defineList[$kind]['columns'][$column_id]['columns']))
                                        {
                                            //複数カラムの合計

                                        }
                                    }
                                }
                            }
                        }
                        //進捗進める
                        $progress++;
                        if($progress % (ceil($progress_total / $progress_scale)) == 0)
                        {
                            $t024_job_state->setProgress($job_id, $progress);
                        }
                    }
                    break;
            }
            //checked_atはemployeeごとに調べる
            $checked_state = $t024_job_state->getJobStateWithCheckedAt($job_id);
            if($checked_state != null)
            {
                //時間比較して最終アクセスより10秒以上アクセス経過していたら終了する
                if($checked_state['state'] == 3 || 10 < Carbon::now()->diffInSeconds($checked_state['checked_at']))
                {
                    $t024_job_state->cancelJob($job_id);
                    $state = 3;
                    break;
                }
            }
        }
        //最後にprogressをupdate
        $t024_job_state->setProgress($job_id, $progress);
        try
        {
            //キャンセルの場合
            $filePath = $cf->getFilePath(1, $job_id);
            //出力用publicファイルパス取得
            $filePath_csv = $cf->getFilePath(3, $job_id);
            if($state == 3)
            {
                //途中生成のファイルがあれば削除
                if($is_csv)
                {
                    if(Storage::disk('public')->exists($filePath_csv))
                    {
                        Storage::disk('public')->delete($filePath_csv);
                    }
                }
                else
                {
                    if(Storage::disk('local')->exists($filePath))
                    {
                        Storage::disk('local')->delete($filePath);
                    }
                }
            }
            //キャンセル以外
            else
            {
                if($is_csv)
                {
                    //targetListをdetail_no順にソート
                    $list_size = 0;
                    foreach($targetList as $kind_list)
                    {
                        $list_size += count($kind_list['detail_no']);
                    }
                    //配列のdeteil_noを順に探して昇順に並べる
                    $detail_numbered_list = [];
                    for($i = 0; $i < $list_size; $i++)
                    {
                        foreach($targetList as $kind_list)
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
                    if(!Storage::disk('public')->exists($filePath_csv))
                    {
                        //ファイル無かったら、ヘッダとBOMと共に生成
                        $header = [];
                        //ヘッダ作成
                        foreach($detail_numbered_list as $value_define)
                        {
                            $header[] = $value_define['name'];
                        }
                        Storage::disk('public')->put($filePath_csv, "\xEF\xBB\xBF" . implode(",", $header));
                    }
                    //employeeループ
                    foreach($ret as $employee_array)
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
                    if($seq_no == -1)
                    {
                        //job_stateを終了に変更
                        $t024_job_state->finishJob($job_id);
                        Log::info(0, 0, "検索結果生成完了 >> job_id = " . $job_id . ", seq_no = " . $seq_no . " to " . $filePath_csv, 999);
                    }
                }
                else
                {
                    //結果をファイル出力
                    $json_text = json_encode($ret);
                    if(!Storage::disk('local')->exists($filePath))
                    {
                        if($seq_no == 0)
                        {
                            //続きがある場合は末尾を加工
                            Log::info(0, 0, "検索結果生成完了 >> job_id = " . $job_id . ", seq_no = " . $seq_no . " to " . $filePath, 999);
                            Storage::disk('local')->put($filePath, substr($json_text, 0, -1) . ",");
                        }
                        else
                        {
                            //1seqのみで終了の場合そのまま出力
                            Log::info(0, 0, "検索結果生成完了 >> job_id = " . $job_id . ", seq_no = " . $seq_no . " to " . $filePath, 999);
                            Storage::disk('local')->put($filePath, $json_text);
                            //job_stateを終了に変更
                            $t024_job_state->finishJob($job_id);
                        }
                    }
                    else if($seq_no != -1)
                    {
                        //追記の場合は先頭と末尾を加工
                        Log::info(0, 0, "検索結果出力 >> job_id = " . $job_id . ", seq_no = " . $seq_no . " to " . $filePath, 999);
                        Storage::disk('local')->append($filePath, substr(substr($json_text, 1), 0, -1) . ",");
                    }
                    else
                    {
                        //追記の場合は先頭を加工
                        Storage::disk('local')->append($filePath, substr($json_text, 1));
                        Log::info(0, 0, "検索結果出力完了 >> job_id = " . $job_id . ", seq_no = " . $seq_no . " to " . $filePath, 999);
                        //job_stateを終了に変更
                        $t024_job_state->finishJob($job_id);
                    }
                }
            }
        }
        catch(Exception $e)
        {
            //job_stateを異常終了に変更
            Log::info(0, 0, "検索結果生成エラー" . $filePath, 999);
            $t024_job_state->errorJob($job_id);
        }
    }
}
