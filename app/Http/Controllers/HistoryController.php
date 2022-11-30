<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m006_post;
use App\Models\m007_employee;
use App\Models\m013_employment_style;
use App\Models\t018_office_history;
use App\Models\t019_work_closing_belonging_office_history;
use App\Models\t020_dept_history;
use App\Models\t021_post_history;
use App\Models\t023_employment_style_history;
use Illuminate\Support\Facades\Schema;

use App\Http\AppLibs\CommonFunctions;
use Illuminate\Support\Facades\DB;
include(dirname(__FILE__).'/../AppLibs/Const.php');

class HistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 履歴テーブル更新
     */
    public function updateHistoryTable(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $data = $request->input('data');
        //登録者
        $employeeID = $request->input('employeeID');
        //登録対象者
        $targetEmployeeID = $request->input('targetEmployeeID');

        $model_m007_employee = new m007_employee();
        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;

        $update_date_serial = $cf->getTodaySerial();
        $update_date = $cf->serialToDate($update_date_serial);

        $model_m004_office = new m004_office();
        $model_m005_dept = new m005_dept();
        $model_m006_post = new m006_post();
        $model_m013_employment_style = new m013_employment_style();

        // 事務所IDとコード
        $data[0]['office_code'] = $model_m004_office->getCode($data[0]['office_id'])->office_code;
        $data[0]['office_name'] = $model_m004_office->getName($data[0]['office_id'])->office_name;
        $data[0]['office_short_name'] = $model_m004_office->getShortName($data[0]['office_id'])->office_short_name;
        // 勤怠締め所属事務所IDとコード
        $data[0]['work_closing_belonging_office_code'] = $model_m004_office->getCode($data[0]['work_closing_belonging_office_id'])->office_code;
        $data[0]['work_closing_belonging_office_name'] = $model_m004_office->getName($data[0]['work_closing_belonging_office_id'])->office_name;
        $data[0]['work_closing_belonging_office_short_name'] = $model_m004_office->getShortName($data[0]['work_closing_belonging_office_id'])->office_short_name;
        // 部署IDとコード
        $data[0]['dept_code'] = $model_m005_dept->getCode($data[0]['dept_id'])->dept_code;
        $data[0]['dept_name'] = $model_m005_dept->getName($data[0]['dept_id'])->dept_name;
        $data[0]['dept_short_name'] = $model_m005_dept->getShortName($data[0]['dept_id'])->dept_short_name;
        // 役職IDとコード
        if($data[0]['post_id'] == null || $data[0]['post_id'] == 0){
            $data[0]['post_id'] = 1;
            $data[0]['post_code'] = '0';
        }else{
            $data[0]['post_code'] = $model_m006_post->getCode($data[0]['post_id'])->post_code;
        }
        $data[0]['post_name'] = $model_m006_post->getName($data[0]['post_id'])->post_name;
        $data[0]['post_short_name'] = $model_m006_post->getShortName($data[0]['post_id'])->post_short_name;
        // 雇用形態
        $data[0]['employment_style_name'] = $model_m013_employment_style->getName($data[0]['employment_style_id'])->employment_style_name;
        $data[0]['employment_style_short_name'] = $model_m013_employment_style->getShortName($data[0]['employment_style_id'])->employment_style_short_name;

        $valid_date_start = $data[0]['valid_date_start'];
        $valid_date_end = $data[0]['valid_date_end'];

        $office_history = new t018_office_history();
        $work_closing_belonging_office_history = new t019_work_closing_belonging_office_history();
        $dept_history = new t020_dept_history();
        $post_history = new t021_post_history();
        $employment_style_history = new t023_employment_style_history();

        $t018_detail_no = $office_history->last_detail_no();
        $t018_detail_no = $t018_detail_no == null ? 1 : $t018_detail_no->detail_no + 1;
        $t019_detail_no = $work_closing_belonging_office_history->last_detail_no();
        $t019_detail_no = $t019_detail_no == null ? 1 : $t019_detail_no->detail_no + 1;
        $t020_detail_no = $dept_history->last_detail_no();
        $t020_detail_no = $t020_detail_no == null ? 1 : $t020_detail_no->detail_no + 1;
        $t021_detail_no = $post_history->last_detail_no();
        $t021_detail_no = $t021_detail_no == null ? 1 : $t021_detail_no->detail_no + 1;
        $t023_detail_no = $employment_style_history->last_detail_no();
        $t023_detail_no = $t023_detail_no == null ? 1 : $t023_detail_no->detail_no + 1;

        // 事務所履歴更新
        HistoryController::updateHistory($data[0], 'office', 'office', $targetEmployeeID, $office_history, $valid_date_start, $valid_date_end, $t018_detail_no, $userCode, $update_date);
        // 勤怠締め所属事務所履歴更新
        HistoryController::updateHistory($data[0], 'work_closing_belonging_office', 'office', $targetEmployeeID, $work_closing_belonging_office_history, $valid_date_start, $valid_date_end, $t019_detail_no, $userCode, $update_date);
        // 部署履歴更新
        HistoryController::updateHistory($data[0], 'dept', 'dept', $targetEmployeeID, $dept_history, $valid_date_start, $valid_date_end, $t020_detail_no, $userCode, $update_date);
        // 役職履歴更新
        HistoryController::updateHistory($data[0], 'post', 'post', $targetEmployeeID, $post_history, $valid_date_start, $valid_date_end, $t021_detail_no, $userCode, $update_date);
        // 雇用形態履歴更新
        HistoryController::updateHistory($data[0], 'employment_style', 'employment_style', $targetEmployeeID, $employment_style_history, $valid_date_start, $valid_date_end, $t023_detail_no, $userCode, $update_date);

        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * 履歴テーブル更新
     */
    public function updateHistoryTableFromFlie(Request $request)
    {
        //共通関数
        $cf = new CommonFunctions();

        $data = $request->input('data');
        //登録者
        $employeeID = $request->input('employeeID');

        $model_m007_employee = new m007_employee();
        $userCode = $model_m007_employee->getSimpleEmployeeDataByID($employeeID)->employee_code;
        
        //会社ID
        $company_id = $request->session()->get('employee')->company_id;

        $update_date_serial = $cf->getTodaySerial();
        $update_date = $cf->serialToDate($update_date_serial);

        $valid_date_start = $request->input('valid_date_start');
        $valid_date_end = $request->input('valid_date_end');

        $model_m004_office = new m004_office();
        $model_m005_dept = new m005_dept();
        $model_m006_post = new m006_post();
        $model_m013_employment_style = new m013_employment_style();
        $office_history = new t018_office_history();
        $work_closing_belonging_office_history = new t019_work_closing_belonging_office_history();
        $dept_history = new t020_dept_history();
        $post_history = new t021_post_history();
        $employment_style_history = new t023_employment_style_history();

        foreach($data as $info)
        {
            // 事務所IDとコード
            $info['office_id'] = $model_m004_office->getIDByCode($info['office_code'], $company_id)->office_id;
            $info['office_code'] = $model_m004_office->getCode($info['office_id'])->office_code;
            $info['office_name'] = $model_m004_office->getName($info['office_id'])->office_name;
            $info['office_short_name'] = $model_m004_office->getShortName($info['office_id'])->office_short_name;
            // 勤怠締め所属事務所IDとコード
            $info['work_closing_belonging_office_id'] = $model_m004_office->getIDByCode($info['work_closing_belonging_office_code'], $company_id)->office_id;
            $info['work_closing_belonging_office_code'] = $model_m004_office->getCode($info['work_closing_belonging_office_id'])->office_code;
            $info['work_closing_belonging_office_name'] = $model_m004_office->getName($info['work_closing_belonging_office_id'])->office_name;
            $info['work_closing_belonging_office_short_name'] = $model_m004_office->getShortName($info['work_closing_belonging_office_id'])->office_short_name;
            // 部署IDとコード
            $info['dept_id'] = $model_m005_dept->getIDByCode($info['dept_code'],$company_id)->dept_id;
            $info['dept_code'] = $model_m005_dept->getCode($info['dept_id'])->dept_code;
            $info['dept_name'] = $model_m005_dept->getName($info['dept_id'])->dept_name;
            $info['dept_short_name'] = $model_m005_dept->getShortName($info['dept_id'])->dept_short_name;
            // 役職IDとコード
            $info['post_id'] = $model_m006_post->getIDByCode($info['post_code'], $company_id)->post_id;
            if($info['post_id'] == null || $info['post_id'] == 0){
                $info['post_id'] = 1;
                $info['post_code'] = '0';
                $info['post_name'] = 'なし';
                $info['post_short_name'] = 'なし';
            }else{
                $info['post_code'] = $model_m006_post->getCode($info['post_id'])->post_code;
                $info['post_name'] = $model_m006_post->getName($info['post_id'])->post_name;
                $info['post_short_name'] = $model_m006_post->getShortName($info['post_id'])->post_short_name;
            }
            // 雇用形態
            $info['employment_style_name'] = $model_m013_employment_style->getName($info['employment_style_id'])->employment_style_name;
            $info['employment_style_short_name'] = $model_m013_employment_style->getShortName($info['employment_style_id'])->employment_style_short_name;

            $t018_detail_no = $office_history->last_detail_no();
            $t018_detail_no = $t018_detail_no == null ? 1 : $t018_detail_no->detail_no + 1;
            $t019_detail_no = $work_closing_belonging_office_history->last_detail_no();
            $t019_detail_no = $t019_detail_no == null ? 1 : $t019_detail_no->detail_no + 1;
            $t020_detail_no = $dept_history->last_detail_no();
            $t020_detail_no = $t020_detail_no == null ? 1 : $t020_detail_no->detail_no + 1;
            $t021_detail_no = $post_history->last_detail_no();
            $t021_detail_no = $t021_detail_no == null ? 1 : $t021_detail_no->detail_no + 1;
            $t023_detail_no = $employment_style_history->last_detail_no();
            $t023_detail_no = $t023_detail_no == null ? 1 : $t023_detail_no->detail_no + 1;

            // 事務所履歴更新
            HistoryController::updateHistory($info, 'office', 'office', $info['employee_id'], $office_history, $valid_date_start, $valid_date_end, $t018_detail_no, $userCode, $update_date);
            // 勤怠締め所属事務所履歴更新
            HistoryController::updateHistory($info, 'work_closing_belonging_office', 'office', $info['employee_id'], $work_closing_belonging_office_history, $valid_date_start, $valid_date_end, $t019_detail_no, $userCode, $update_date);
            // 部署履歴更新
            HistoryController::updateHistory($info, 'dept', 'dept', $info['employee_id'], $dept_history, $valid_date_start, $valid_date_end, $t020_detail_no, $userCode, $update_date);
            // 役職履歴更新
            HistoryController::updateHistory($info, 'post', 'post', $info['employee_id'], $post_history, $valid_date_start, $valid_date_end, $t021_detail_no, $userCode, $update_date);
            // 雇用形態履歴更新
            HistoryController::updateHistory($info, 'employment_style', 'employment_style', $info['employee_id'], $employment_style_history, $valid_date_start, $valid_date_end, $t023_detail_no, $userCode, $update_date);
        }
        return response()->json([
            'result' => true,
        ]);
    }

    /**
     * 履歴更新処理
     */
    public static function updateHistory($data, $target_name, $master_name, $employee_id, $model, $valid_date_start, $valid_date_end, $detail_no, $userCode, $update_date){
        $history_data = $model->getDataOfEmployee($employee_id);
        $employee = m007_employee::find($employee_id);

        $master_id_name = $master_name . '_id';
        $master_code_name = $master_name . '_code';
        $master_name_name = $master_name . '_name';
        $master_short_name_name = $master_name . '_short_name';

        $target_id_name = $target_name . '_id';
        $target_code_name = $target_name . '_code';
        $target_name_name = $target_name . '_name';
        $target_short_name_name = $target_name . '_short_name';

        $input_id = $data[$target_id_name];
        $table_name = $model->getTable();
        if(Schema::hasColumn($table_name, $master_code_name)){
            $input_code = $data[$target_code_name];
            $has_column_code = true;
        }
        else{
            $input_code = 0;
            $has_column_code = false;
        }
        $input_name = $data[$target_name_name];
        $input_short_name = $data[$target_short_name_name];

        //履歴がない場合、最初に全期間履歴挿入
        if($history_data->isEmpty()){
            if($valid_date_start != 0){
                $model->insertData($employee_id, $employee->$master_id_name, $employee->$master_name->$master_code_name, $employee->$master_name->$master_name_name, $employee->$master_name->$master_short_name_name, 0, $valid_date_start - 1, $detail_no, $userCode, $update_date);
                $detail_no += 1;
            }
            if($valid_date_end != DATE_SERIAL_MAX){
                $model->insertData($employee_id, $employee->$master_id_name, $employee->$master_name->$master_code_name, $employee->$master_name->$master_name_name, $employee->$master_name->$master_short_name_name, $valid_date_end + 1, DATE_SERIAL_MAX, $detail_no, $userCode, $update_date);
                $detail_no += 1;
            }
            $model->insertData($employee_id, $input_id, $input_code, $input_name, $input_short_name, $valid_date_start, $valid_date_end, $detail_no, $userCode, $update_date);
        }
        else{
            $primary = $model->first()->getKeyName();
            foreach($history_data as $hisdata){ //履歴ある場合は既存履歴の更新をする
                if($hisdata->valid_date_end <= $valid_date_end){ //履歴endが指定end以前
                    if($hisdata->valid_date_start < $valid_date_start && $hisdata->valid_date_end < $valid_date_start){ //履歴と指定期間被らない
                        continue;
                    }
                    $model->updatePreviousData($hisdata->$primary, $hisdata->valid_date_start, $valid_date_start - 1, $userCode, $update_date); //履歴endを指定start-1で更新
                }
                else{
                    if($valid_date_start <= $hisdata->valid_date_start){ //履歴startが指定start以後
                        if($valid_date_end < $hisdata->valid_date_end && $valid_date_end < $hisdata->valid_date_start){ //履歴と指定期間被らない
                            continue;
                        }
                        $model->updatePreviousData($hisdata->$primary, $valid_date_end + 1, $hisdata->valid_date_end, $userCode, $update_date); //履歴startを指定end+1で更新
                    }
                    else{
                        $model->updatePreviousData($hisdata->$primary, $hisdata->valid_date_start, $valid_date_start - 1, $userCode, $update_date); //履歴endを指定start-1で更新
                        $hisdata_code = $has_column_code ? $hisdata->$master_code_name : 0;
                        $model->insertData($employee_id, $hisdata->$master_id_name, $hisdata_code, $hisdata->$master_name_name, $hisdata->$master_short_name_name, $valid_date_end + 1, $hisdata->valid_date_end, $detail_no, $userCode, $update_date); //履歴startを指定end+1にしたデータを挿入
                        $detail_no += 1;
                    }
                }
            }
            $model->insertData($employee_id, $input_id, $input_code, $input_name, $input_short_name, $valid_date_start, $valid_date_end, $detail_no, $userCode, $update_date); //指定データの履歴挿入
        }
    }
}