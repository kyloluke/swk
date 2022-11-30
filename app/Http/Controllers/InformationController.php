<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\m001_information_type;
use App\Models\m002_information;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\t025_information_read;
use App\Http\AppLibs\CommonFunctions;

class InformationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    /**
     * インフォメーション種別ごとの数を取得
     */
    public function getInformation(Request $request)
    {
        //ログイン中の社員情報を取得
        $employee_info = $request->session()->get('employee');
        $office_id = $employee_info->office_id;
        $dept_id = $employee_info->dept_id;
        $employee_id = $employee_info->employee_id;

        //種別ごとにカウント取得
        $mdm002Information = new m002_information();
        $model_t025_information_read = new t025_information_read();
        $ret = [];
        
        $information_info = $mdm002Information->getInformationDataWithoutType($office_id, $dept_id);
        foreach($information_info as $info)
        {
            $information_type_name = m001_information_type::find($info->information_type_id)->information_type_name;
            $is_readed = 0;
            $information_read_info = $model_t025_information_read->getreaded($employee_id,$info->information_id);
            if($information_read_info != null){
                $is_readed = 1;
            }
            $data_info = [
                'information_id' => $info->information_id,
                'information_type_id' => $info->information_type_id,
                'information_type_name' => $information_type_name,
                'information_subject_name' => $info->information_subject_name,
                'information_contants' => $info->information_contants,
                'valid_date_start' => $info->valid_date_start,
                'valid_date_end' => $info->valid_date_end,
                'updated_at' => $info->updated_at,
                'created_at' => $info->created_at,
                'is_readed' => $is_readed,
            ];
            array_push($ret, $data_info);
        }
        // ソートキー
        $information_id_sort = array_column($ret, 'information_id');
        array_multisort($information_id_sort, SORT_DESC,$ret);

        return response()->json($ret);
    }

    /**
     * インフォメーション種別ごとの数を取得
     */
    public function getAllInformation(Request $request)
    {
        //ログイン中の社員情報を取得
        $companyID = Auth::user()->company_id;

        //種別ごとにカウント取得
        $mdm002Information = new m002_information();
        $ret = [];
        
        $information_info = $mdm002Information->getAllInformation($companyID);
        foreach($information_info as $info)
        {
            $information_type_name = m001_information_type::find($info->information_type_id)->information_type_name;
            $office_name = "全社";
            if($info->display_office_id !== 0){
                $office_name = m004_office::find($info->display_office_id)->office_name;
            }
            $dept_name = "全部署";
            if($info->display_dept_id !== 0){
                $dept_name = m005_dept::find($info->display_dept_id)->dept_name;
            }
            $data_info = [
                'information_id' => $info->information_id,
                'information_type_id' => $info->information_type_id,
                'information_type_name' => $information_type_name,
                'office_id' => $info->display_office_id,
                'office_name' => $office_name,
                'dept_id' => $info->display_dept_id,
                'dept_name' => $dept_name,
                'information_subject_name' => $info->information_subject_name,
                'information_contants' => $info->information_contants,
                'valid_date_start' => $info->valid_date_start,
                'valid_date_end' => $info->valid_date_end,
            ];
            array_push($ret, $data_info);
        }
        // ソートキー
        $information_id_sort = array_column($ret, 'information_id');
        array_multisort($information_id_sort, SORT_ASC,$ret);

        return response()->json([
            'result' => true,
            'values' => [
                'information_info' => $ret,
            ],
        ]);
    }

    /**
     * インフォメーション更新
     */
    public function editInformation(Request $request)
    {

        //ログイン中の社員情報を取得
        $companyID = Auth::user()->company_id;

        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください。(param is null)",
                ],
            ]);
        }
        //インフォメーション情報を取得
        $information_id = $params['information_id'];
        $information_type_id = $params['information_type_id'];
        $office_id = $params['office_id'];
        $information_subject_name = $params['information_subject_name'];
        $information_contants = $params['information_contants'];
        $valid_date_start = $params['valid_date_start'];
        $valid_date_end = $params['valid_date_end'];

        if($information_type_id === null || $information_type_id === 0 || $information_type_id === '0'){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "種別を選択してください",
                ]
            ]);
        }
        if($office_id === null){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "事務所を選択してください",
                ]
            ]);
        }
        if($information_subject_name === null || $information_subject_name === ''){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "件名を入力してください",
                ]
            ]);
        }
        if(mb_strlen($information_subject_name) > 100){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "件名は100文字以下としてください",
                ]
            ]);
        }
        if($information_contants === null || $information_contants === ''){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "内容を入力してください",
                ]
            ]);
        }
        if($valid_date_start === null){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "有効年月日開始を入力してください",
                ]
            ]);
        }
        if($valid_date_end === null){
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "有効年月日終了を入力してください",
                ]
            ]);
        }
        
        $mdm002Information = new m002_information();
        $result = 0;
        $message = '';
        if($information_id === 0){
            //新規作成
            $result = $mdm002Information->insertInformation($companyID, $information_type_id, $office_id, $information_subject_name, $information_contants, $valid_date_start, $valid_date_end);
            $message = 'インフォメーションを登録しました';
        }else{
            //更新
            $result = $mdm002Information->updateInformation($information_id, $information_type_id, $office_id, $information_subject_name, $information_contants, $valid_date_start, $valid_date_end);
            $message = 'インフォメーションを更新しました';
        }

        if($result || $result === 0){
            return response()->json([
                'result' => true,
                'values' => [
                    'message' => $message,
                ]
            ]);
        }else{
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '登録処理に失敗しました。入力内容を確認してください。',
                ]
            ]);
        }
    }

    /**
     * インフォメーション削除
     */
    public function deleteInformation(Request $request)
    {
        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください。(param is null)",
                ],
            ]);
        }
        //インフォメーション情報を取得
        $information_id = $params['information_id'];

        $mdm002Information = new m002_information();
        $result = $mdm002Information->deleteInformation($information_id);
        if($result){
            return response()->json([
                'result' => true,
                'values' => [
                    'message' => '削除しました。',
                ]
            ]);
        }else{
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => '削除失敗しました。',
                ]
            ]);
        }
    }
    
    
    /**
     * インフォメーションを既読にする
     */
    public function readCheckedInformation(Request $request){

        $cf = new CommonFunctions();

        //入力値検証
        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください。(param is null)",
                ],
            ]);
        }
        $info_array = $params['info_array'];

        if($info_array == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください(info is null)",
                ],
            ]);
        }
        //ログイン中の社員情報を取得
        $employee_info = $request->session()->get('employee');
        $employee_id = $employee_info->employee_id;
        $employee_code = $employee_info->employee_code;
        $model_t025_information_read = new t025_information_read();
        $update_date_serial = $cf->getTodaySerial();
        $update_date = $cf->serialToDate($update_date_serial);

        foreach ($info_array as $info) {
            $is_success = $model_t025_information_read->readInformation($employee_id, $info['information_id'], $employee_code);
            if(!$is_success)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なリクエストが行われました。ページを再読み込みしてください(DB access failed)",
                    ],
                ]);
            }
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => "",
            ],
        ]);
    }
    /**
     * インフォメーションを未読にする
     */
    public function unreadCheckedInformation(Request $request){

        $cf = new CommonFunctions();

        //入力値検証
        $params = $request->input('params');
        if($params == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください。(param is null)",
                ],
            ]);
        }
        $info_array = $params['info_array'];

        if($info_array == null)
        {
            return response()->json([
                'result' => false,
                'values' => [
                    'message' => "不正なリクエストが行われました。ページを再読み込みしてください(info is null)",
                ],
            ]);
        }
        //ログイン中の社員情報を取得
        $employee_info = $request->session()->get('employee');
        $employee_id = $employee_info->employee_id;
        $employee_code = $employee_info->employee_code;
        $model_t025_information_read = new t025_information_read();
        $update_date_serial = $cf->getTodaySerial();
        $update_date = $cf->serialToDate($update_date_serial);

        foreach ($info_array as $info) {
            $is_success = $model_t025_information_read->unreadInformation($employee_id, $info['information_id'], $employee_code, $update_date);
            if(!$is_success)
            {
                return response()->json([
                    'result' => false,
                    'values' => [
                        'message' => "不正なリクエストが行われました。ページを再読み込みしてください(DB access failed)",
                    ],
                ]);
            }
        }

        return response()->json([
            'result' => true,
            'values' => [
                'message' => "",
            ],
        ]);
    }
}
