<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\m001_information_type;
use App\Models\m002_information;
use App\Models\m003_company;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m006_post;
use App\Models\m007_employee;
use App\Models\m008_system_administrator;
use App\Models\m009_display_color;
use App\Models\m010_message;
use App\Models\m011_authority_pattern;
use App\Models\m012_work_style;
use App\Models\m013_employment_style;
use App\Models\m014_over_time_class;
use App\Models\m015_deduction_reason;
use App\Models\m016_close_date;
use App\Models\m017_rest_time;
use App\Models\m018_approval_state;
use App\Models\m019_close_state;
use App\Models\m021_calendar;
use App\Models\m022_calendar_setting;
use App\Models\m023_work_zone;
use App\Models\m024_work_zone_time;
use App\Models\m025_clocking_in_out;
use App\Models\m027_work_holiday;
use App\Models\m029_theme;
use App\Models\m030_work_achievement;
use App\Models\m031_unemployed;
use App\Models\m037_violation_warning;
use App\Models\m038_violation_warning_type;
use App\Models\m039_prevention_overwork_check;
use App\Models\m040_36agreement_apply;
use App\Models\m043_holiday;
use App\Models\t001_web_punch_clock;
use App\Models\t002_attendance_information;
use App\Models\t003_attendance_aggregate;
use App\Models\t004_substitute_information;
use App\Models\t007_over_time_achievement;
use App\Models\t008_unemployed_information;
use App\Models\t010_acquired_holiday;
use App\Models\t024_job_state;
use App\Models\t026_general_search_save;
use App\Models\t027_general_search_save_param;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\GeneralSearchFunctions;
use App\Jobs\GeneralSearch;
use App\Jobs\GeneralSearchConvert;
use Illuminate\Support\Facades\Storage;
use App\Http\AppLibs\LogFunctions as Log;

class GeneralSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 汎用検索用の定義リストを返す
     */
    public function getGeneralSearchList(Request $request)
    {
        $general_search_func = new GeneralSearchFunctions();
        //ToDo クライアントで不要なデータを削除して渡す　※DB周りはオブジェクトで解釈できない & カラム名などはクライアントを信用しない形とするので暫定として対応なし
        //クライアントへリストを返す
        return response()->json([
            'result' => true,
            'values' => [
                'defineList' => $general_search_func->defineGeneralSearchList()
            ],
        ]);
    }

    /**
     * 定義リストに従った汎用検索実施
     */
    public function execGeneralSearchByDefineList(Request $request)
    {
        $cf = new CommonFunctions();
        $general_search_func = new GeneralSearchFunctions();
        $model_m007_employee = new m007_employee();
        $model_m004_office = new m004_office();
        //リクエストからリストの定義を取得
        $targetList = $request->input('targetList');
        if($targetList == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "検索条件が未指定です。ページを再読み込みしてください",
                ],
            ]);
        }
        $defineList = $general_search_func->defineGeneralSearchList();
        //検索条件リストは、以下の形式を想定
        // [
        //     kind: 0
        //     columns:[0, 1]
        // ],
        // [
        //     kind: 1
        //     columns:[0, 1]
        // ],
        foreach($targetList as $target)
        {
            if(!isset($target['kind']))
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(kind)",
                    ],
                ]);
            }
            else if(!isset($defineList[$target['kind']]))
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(kind is not defined)",
                    ],
                ]);
            }
            else
            {
                //kindは適正
                $cols = $target['columns'];
                $item = $defineList[$target['kind']];
                foreach($cols as $col)
                {
                    if(!isset($item['columns'][$col]))
                    {
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(column)",
                            ],
                        ]);
                    }
                }
            }
        }
        //検索条件
        $conditions = $request->input('conditions');
        if($conditions == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "検索条件が未指定です。ページを再読み込みしてください。",
                ],
            ]);
        }
        //必須項目
        if(!isset($conditions['start_date']))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "検索条件が未指定です。ページを再読み込みしてください。",
                ],
            ]);
        }
        if(!isset($conditions['end_date']))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "検索条件が未指定です。ページを再読み込みしてください。",
                ],
            ]);
        }
        if(!isset($conditions['unit_type']))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "検索条件が未指定です。ページを再読み込みしてください。",
                ],
            ]);
        }
        $start_date = $conditions['start_date'];
        $end_date = $conditions['end_date'];
        $unit_type = $conditions['unit_type'];
        $days = 0;
        //月指定の場合は比較
        if($unit_type == 2)
        {
            $months = $cf->compaireYearMonth($start_date, $end_date);
            if($months < 0)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "検索条件が未指定です。ページを再読み込みしてください。",
                    ],
                ]);
            }
            $days = $months + 1;
        }
        else
        {
            //日単位
            $days = $end_date - $start_date + 1;
        }
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;
        $employee_list = [];
        if(isset($conditions['employee_code']))
        {
            $employee_code = $conditions['employee_code'];
            if ($conditions['is_office_page']){
                $employee = $model_m007_employee->getEmployeeIdNameOnlyOffice($employee_code, $company_id, $conditions['office_id']);
            }else{
                $employee = $model_m007_employee->getEmployeeIdNameNoAdmin($employee_code, $company_id);
            }
            if($employee == null)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "社員コードに該当する社員が見つかりませんでした。指定した社員コードを確認してください。",
                    ],
                ]);
            }
            $employee_list[] = $employee->employee_id;
        }

        //事業所（0の場合は全社検索）
        //社員コード指定の場合は無視
        if(count($employee_list) == 0)
        {
            if(isset($conditions['office_id']))
            {
                $employee_array = [];
                if($conditions['office_id'] == 0)
                {
                    //全社員対象(管理者以外)
                    $employee_array = $model_m007_employee->getAllEmployeeBelongCompanyNoAdmin($company_id);
                }
                else
                {
                    $office = m004_office::find(intval($conditions['office_id']));
                    if($office == null)
                    {
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "該当する事業所が見つかりませんでした。指定した事業所を確認してください",
                            ],
                        ]);
                    }
                    $employee_array = $model_m007_employee->getAllEmployeeBelongOfficeNoAdmin($office->office_id);
                }
                foreach($employee_array as $employee)
                {
                    $employee_list[] = $employee->employee_id;
                }
            }
        }

        //Jobへ登録
        $t024_job_state = new t024_job_state();
        $employee_id = Auth::id();
        $job_name = "汎用検索";
        $job_id = $t024_job_state->createJobEmployee($job_name, $employee_id);

        //合計件数
        $progress_total = count($employee_list) * $days;
        // check 0 progress total
        if($progress_total == 0){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "社員コードに該当する社員が見つかりませんでした。指定した社員コードを確認してください。",
                ],
            ]);
        }

        //合計件数によりJobを複数に分ける　※分割数は適当。要調整だけれど、細かい分には問題ないかもしれない
        $employee_slice_size = 50;
        if(31 < $days)
        {
            $employee_slice_size = 25;
        }
        else if(90 < $days)
        {
            $employee_slice_size = 5;
        }
        $employee_list_array = [];
        for($i = 0; $i < ceil(count($employee_list) / $employee_slice_size); $i++)
        {
            $employee_list_array[] = array_slice($employee_list, $i * $employee_slice_size, $employee_slice_size);
        }
        //キューに追加
        for($i = 0; $i < count($employee_list_array); $i++)
        {
            //最後の処理のみseq_noに-1を渡す
            Log::info(0, 0, "汎用検索Job登録 >> job_id = " . $job_id . ", seq_no = " . ($i == count($employee_list_array) - 1 ? -1 : $i), 999);
            //GeneralSearch::dispatch($targetList, $employee_list_array[$i], $start_date, $end_date, $unit_type, $job_id, $i == count($employee_list_array) - 1 ? -1 : $i, 1000 <= $progress_total);
            GeneralSearch::dispatch($targetList, $employee_list_array[$i], $start_date, $end_date, $unit_type, $job_id, $i == count($employee_list_array) - 1 ? -1 : $i, false);
        }

        //JobIDを返す
        return response()->json([
            'result' => true,
            'values' => [
                'job_id' => $job_id,
                'progress_total' => $progress_total,
            ]
        ]);
    }
    /**
     * 検索状態のチェック
     */
    public function checkState(Request $request)
    {
        //JobIDを指定して状態を取得　※ユーザー本人指定のJobIDかチェック
        $t024_job_state = new t024_job_state();
        $job_id = $request->input('jobID');
        if($job_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id)',
                ]
            ]);
        }
        //状態を返す
        $state = $t024_job_state->getJobProgress($job_id);
        if($state == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id not found)',
                ]
            ]);
        }
        if(!$t024_job_state->isPermited($job_id, Auth::id()))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'permission denied',
                ]
            ]);
        }
        return response()->json([
            'result' => true,
            'values' => [
                'state' => $state,
            ]
        ]);
    }
    /**
     * 検索結果のダウンロード
     */
    public function downloadResult(Request $request)
    {
        $cf = new CommonFunctions();

        //リクエストからJobID取得
        $t024_job_state = new t024_job_state();
        $job_id = $request->input('jobID');
        if($job_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id)',
                ]
            ]);
        }
        //JobIDを指定して状態を取得　※ユーザー本人指定のJobIDかチェック
        $state = $t024_job_state->getJobState($job_id);
        if($state == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id not found)',
                ]
            ]);
        }
        if(!$t024_job_state->isPermited($job_id, Auth::id()))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'permission denied',
                ]
            ]);
        }

        //JobIDからディレクトリを特定
        $filePath = $cf->getFilePath(1, $job_id);
        Log::info(0, 0, "汎用検索結果ダウンロード >> " . $filePath, 999);
        
        //ファイル読み込み
        $contents = Storage::disk('local')->get($filePath);
        Storage::disk('local')->delete($filePath);

        //ファイルをダウンロードさせ、削除
        return response()->json([
            'result' => true,
            'values' => [
                'content' => json_decode($contents),
            ]
        ]);
    }
    public function mekaDownloadFile(Request $request)
    {
        $cf = new CommonFunctions();

        //リクエストからJobID取得
        $t024_job_state = new t024_job_state();
        $job_id = $request->input('jobID');
        if($job_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id)',
                ]
            ]);
        }
        //JobIDを指定して状態を取得　※ユーザー本人指定のJobIDかチェック
        $state = $t024_job_state->getJobState($job_id);
        if($state == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id not found)',
                ]
            ]);
        }
        if(!$t024_job_state->isPermited($job_id, Auth::id()))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'permission denied',
                ]
            ]);
        }

        //出力用publicファイルパス取得
        $filePath_csv = $cf->getFilePath(3, $job_id);
        
        return response()->json([
            'result' => true,
            'values' => [
                'filePath' => 'storage/' . $filePath_csv,
            ]
        ]);
    }
    public function downloadByStream(Request $request)
    {
        $cf = new CommonFunctions();
        $target_list = $request->input('targetList');
        $job_id = $request->input('jobID');

        //JobIDからディレクトリを特定
        $filePath = $cf->getFilePath(1, $job_id);
        //ファイル読み込み
        $contents = Storage::disk('local')->get($filePath);
        //JSON文字列をCSVに変換
        $contents_json = json_decode($contents, true);
        
        $target_list = json_decode(base64_decode($target_list), true);
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
        $stream = fopen('php://temp', 'w');
        $header = [];
        //ヘッダ作成
        foreach($detail_numbered_list as $value_define)
        {
            $header[] = $value_define['name'];
        }
        fputcsv($stream, str_replace(PHP_EOL, "\r\n", array_values((array)$header))); 
        //Storage::disk('public')->put($filePath_csv, "\xEF\xBB\xBF" . implode(",", $header));
        
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
                fputcsv($stream, str_replace(PHP_EOL, "\r\n", array_values((array)$list_data))); 
                //Storage::disk('public')->append($filePath_csv, implode(",", $list_data));
            }
        }

        rewind($stream); //注意：fpassthru() する前にもファイルポインタは戻しておく
    
        return response()->stream(function () use ($stream) { //修正 2. ストリームのままCSV出力できるようにする
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=t_logs.csv"
        ]);
    }
    /**
     * CSVファイルをダウンロード（aタグからの呼び出しを想定）
     */
    public function generalSearchDownloadAsCSV($job_id)
    {
        $cf = new CommonFunctions();

        //出力されたファイルパス取得
        $filePath_csv = $cf->getFilePath(2, $job_id);

        $file_name = "汎用検索_" . $cf->serialToYearMonthDayNumber($cf->getTodaySerial()) . ".csv";

        $headers = [
            'Content-Type' => "text/csv",
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"'
        ];
        //ダウロードファイルとしてレスポンス
        return response()->make(Storage::disk('local')->get($filePath_csv), 200, $headers);
    }
    public function generateDownloadURL(Request $request)
    {
        
    }
    /**
     * 検索キャンセル実行
     */
    public function generalSearchCancel(Request $request)
    {
        //リクエストからJobID取得
        $t024_job_state = new t024_job_state();
        $job_id = $request->input('jobID');
        if($job_id == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id)',
                ]
            ]);
        }
        //JobIDを指定して状態を取得　※ユーザー本人指定のJobIDかチェック
        $state = $t024_job_state->getJobState($job_id);
        if($state == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'param error(job state id not found)',
                ]
            ]);
        }
        if(!$t024_job_state->isPermited($job_id, Auth::id()))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => 'permission denied',
                ]
            ]);
        }
        //ジョブをキャンセル
        $t024_job_state->cancelJob($job_id);
        
        return response()->json([
            'result' => true,
            'values' => [
                'jobID' => $job_id,
            ]
        ]);
    }
    /**
     * 検索条件一覧取得 
     */
    public function getGenralSearchSaveList(Request $request){
        //パラメータ不要
        $t026_general_search_save = new t026_general_search_save();
        //ログイン社員IDと会社IDを取得
        $companyID = Auth::user()->company_id;
        $employeeID = Auth::user()->employee_id;

        //リスト取得
        $saveList = $t026_general_search_save->getGeneralSearchSaveList($companyID, $employeeID);

        return response()->json([
            'result' => true,
            'values' => [
                'commonList' => $saveList['common_list'],
                'personalList' => $saveList['personal_list']
            ]
        ]);
    }
    /**
     * 保存した条件指定取得
     */
    public function getGeneralSearchSave(Request $request){
        $t027_general_search_save_param = new t027_general_search_save_param();
        $saveID = $request->input('saveID');
        //ログイン社員IDと会社IDを取得
        $companyID = Auth::user()->company_id;
        $employeeID = Auth::user()->employee_id;
        if($saveID == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "保存IDの指定が不正です",
                ],
            ]);
        }
        //権限あるかチェック
        $general_search_save = t026_general_search_save::find($saveID);
        if($general_search_save == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正な保存IDが指定されました",
                ],
            ]);
        }
        if($general_search_save->share_class == 0)
        {
            //共有の場合は会社IDにてチェック
            if($companyID != $general_search_save->company_id)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "権限がありません（会社ID）",
                    ],
                ]);
            }
        }
        else if($general_search_save->share_class == 1)
        {
            //個人用の場合は社員IDにてチェック
            if($employeeID != $general_search_save->employee_id)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "権限がありません（社員ID）",
                    ],
                ]);
            }
        }
        else
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正な保存IDが指定されました",
                ],
            ]);
        }
        //権限チェックOK
        //保存条件取得
        $params = $t027_general_search_save_param->getSaveParam($saveID);

        return response()->json([
            'result' => true,
            'values' => [
                'general_search_save' => $general_search_save,
                'general_search_save_params' => $params,
            ]
        ]);
    }
    /**
     * 保存名チェック（重複しているかチェック）
     */
    public function checkGeneralSearchSaveName(Request $request){
        $t026_general_search_save = new t026_general_search_save();
        $saveName = $request->input('saveName');
        $shareClass = $request->input('shareClass');
        
        if($saveName == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "保存名の指定が不正です",
                ],
            ]);
        }
        //共有タイプは0か1のみ
        if($shareClass == null || !($shareClass == 0 || $shareClass == 1))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "保存タイプの指定が不正です",
                ],
            ]);
        }
        //ログイン者の会社IDと社員ID取得
        $companyID = Auth::user()->company_id;
        $employeeID = Auth::user()->employee_id;

        $generalSearchSaveID = $t026_general_search_save->checkIsDupulicateSaveName($saveName, $shareClass, $companyID, $employeeID);
        if($generalSearchSaveID === null)
        {
            //nullの場合は値が不正だった
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "指定したパラメータが不正です",
                ],
            ]);
        }

        return response()->json([
            'result' => true,
            'values' => [
                'isDupulicate' => $generalSearchSaveID !== 0,
                'saveID' => $generalSearchSaveID,
            ]
        ]);
    }
    /**
     * 検索条件保存（新規／更新）
     */
    public function saveGeneralSearchCondition(Request $request){
        $general_search_func = new GeneralSearchFunctions();
        $t026_general_search_save = new t026_general_search_save();
        $t027_general_search_save_param = new t027_general_search_save_param();
        //リクエストからリストの定義を取得
        $targetList = $request->input('targetList');
        $saveName = $request->input('saveName');
        $shareClass = $request->input('shareClass');
        $unitType = $request->input('unitType');
        $saveID = $request->input('saveID');
        
        if($saveName == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "保存名の指定が不正です",
                ],
            ]);
        }
        if($saveID === null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "IDの指定が不正です",
                ],
            ]);
        }
        //共有タイプは0か1のみ
        if($shareClass === null || !($shareClass == 0 || $shareClass == 1))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "保存タイプの指定が不正です",
                ],
            ]);
        }
        //保存単位は1か2のみ
        if($unitType == null || !($unitType == 1|| $unitType == 2))
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "表示単位の指定が不正です",
                ],
            ]);
        }
        if($targetList == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "検索条件が未指定です。ページを再読み込みしてください",
                ],
            ]);
        }
        $defineList = $general_search_func->defineGeneralSearchList();
        //検索条件リストは、以下の形式を想定
        // [
        //     kind: 0
        //     columns:[0, 1]
        // ],
        // [
        //     kind: 1
        //     columns:[0, 1]
        // ],
        foreach($targetList as $target)
        {
            if(!isset($target['kind']))
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(kind)",
                    ],
                ]);
            }
            else if(!isset($defineList[$target['kind']]))
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(kind is not defined)",
                    ],
                ]);
            }
            else
            {
                //kindは適正
                $cols = $target['columns'];
                $item = $defineList[$target['kind']];
                foreach($cols as $col)
                {
                    if(!isset($item['columns'][$col]))
                    {
                        return response()->json([
                            'result' => false,
                            'values' => [
                                'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(column)",
                            ],
                        ]);
                    }
                }
            }
        }
        //重複チェック
        //ログイン者の会社IDと社員ID取得
        $companyID = Auth::user()->company_id;
        $employeeID = Auth::user()->employee_id;

        $generalSearchSaveID = $t026_general_search_save->checkIsDupulicateSaveName($saveName, $shareClass, $companyID, $employeeID);
        if($generalSearchSaveID === null)
        {
            //nullの場合は値が不正だった
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "指定したパラメータが不正です",
                ],
            ]);
        }
        if($generalSearchSaveID !== 0)
        {
            //入力されたIDと一致チェック
            if($saveID != $generalSearchSaveID)
            {
                //重複IDが違う
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(saveID)",
                    ],
                ]);
            }
            //重複の場合はt026情報取得して、t027のみ上書き保存
            $t027_general_search_save_param->overwriteSaveParam($saveID, $targetList, $employeeID);
        }
        else
        {
            if($saveID != 0)
            {
                //重複扱いとしていないのでNG
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正な検索条件が指定されました。ページを再読み込みしてください(saveID is 0)",
                    ],
                ]);
            }
            //重複していない場合は新規保存
            $generalSearchSaveID = $t026_general_search_save->createGeneralSearchSave($saveName, $shareClass, $companyID, $employeeID, $unitType);
            $t027_general_search_save_param->createSaveParam($generalSearchSaveID, $targetList, $employeeID);
        }

        return response()->json([
            'result' => true,
            'values' => [
                '$generalSearchSaveID' => $generalSearchSaveID
            ]
        ]);
    }

    public function deleteGeneralSearchCondition(Request $request){
        $t026_general_search_save = new t026_general_search_save();
        $t027_general_search_save_param = new t027_general_search_save_param();
        $deleteID = $request->input('delete_id');
        //ログイン社員IDと会社IDを取得
        $companyID = Auth::user()->company_id;
        $employeeID = Auth::user()->employee_id;
        if($deleteID == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "保存IDの指定が不正です",
                ],
            ]);
        }

         //権限あるかチェック
        $general_search_save = t026_general_search_save::find($deleteID);

        if($general_search_save == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正な保存IDが指定されました",
                ],
            ]);
        }
        if($general_search_save->share_class == 0)
        {
            //共有の場合は会社IDにてチェック
            if($companyID != $general_search_save->company_id)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "権限がありません（会社ID）",
                    ],
                ]);
            }
        }
        else if($general_search_save->share_class == 1)
        {
            //個人用の場合は社員IDにてチェック
            if($employeeID != $general_search_save->employee_id)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "権限がありません（社員ID）",
                    ],
                ]);
            }
        }
        else
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正な保存IDが指定されました",
                ],
            ]);
        }
         //権限チェックOK

        // delete general search by id
        $deleteResult = $t026_general_search_save->deleteGeneralSearchSave($deleteID, $employeeID);

        if($deleteResult){
            // complement delete general search params
            $t027_general_search_save_param->deleteSaveParamByGeneralSearchId($deleteID);
            return response()->json([
                'result' => true,
                'values' => [
                    'message' => '削除しました。',
                    'delete_name' => $general_search_save->general_search_save_name
                ]
            ]);
        }else{
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '削除失敗しました。'
                ]
            ]);
        }

    }
}