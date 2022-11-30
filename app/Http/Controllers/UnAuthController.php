<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\m003_company;
use App\Models\m007_employee;
use App\Models\t001_web_punch_clock;
use Illuminate\Support\Facades\Hash;

use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\LogFunctions as Log;

class UnAuthController extends Controller
{
    /**
     * 会社IDと従業員コードから社員IDを取得
     * パラメータ：company_id, employee_code
     */
    public function getEmployeeID(Request $request)
    {
        $company_id = $request->company_id;
        $employee_code = $request->employee_code;
        $mdm007Employee = new m007_employee();
        $res = $mdm007Employee->getEmployeeID($company_id, $employee_code);
        return response()->json($res);
    }
    public function getEmployeeIDByCode(Request $request)
    {
        $company_code = $request->company_code;
        $employee_code = $request->employee_code;
        //会社コードから会社ID
        $mdm003Company = new m003_company();
        $company_id = $mdm003Company->getIdByCode($company_code);
        if($company_id === null){
            return response()->json([
                'result' => false,
                'employee_id' => null
            ]);
        }
        $mdm007Employee = new m007_employee();
        $res = $mdm007Employee->getEmployeeID($company_id, $employee_code);
        return response()->json($res);
    }

    /**
     * 社員コード／会社ID／パスワードを照合して成否判定
     * パラメータ：company_id, employee_code, input_password
     * 戻り値：result:trueの場合、valueに社員ID
     */
    public function checkPassword(Request $request)
    {
        $company_id = $request->company_id;
        $employee_code = $request->employee_code;
        $mdm007Employee = new m007_employee();
        $res = $mdm007Employee->getEmployeeID($company_id, $employee_code);
        if(!$res['result'])
        {
            //ID取得NG
            return response()->json([
                'result' => false,
                'values' => [
                    'error' => '1',
                    'message' => 'ID not found'
                ]
            ]);
        }

        //Hash取得してチェック
        $employee_hash = $mdm007Employee->getHash($res['employee_id']);
        if(Hash::check(strval($request->input_password), $employee_hash->stamping_password))
        {
            //照合OK
            return response()->json([
                'result' => true,
                'values' => [
                    'employee_id' => $res['employee_id']
                ]
            ]);
        }
        else
        {
            //照合NG
            return response()->json([
                'result' => false,
                'values' => [
                    'error' => '2',
                    'message' => 'Password not match'
                ]
            ]);
        }
    }

    /**
     * Web打刻の実行
     */
    public function execWebPunch(Request $request)
    {
        $employee_id = $request->employee_id;
        $clocking_in_out_id = $request->clocking_in_out_id;
        if($employee_id === null || $clocking_in_out_id === null)
        {
            //パラメータ不足
            return response()->json([
                'result' => false,
                'values' => [
                    'error' => '1',
                    'message' => 'Bad parameter'
                ]
            ]);
        }
        
        //現在日時をシリアル値で取得
        $cf = new CommonFunctions();
        $today_serial = $cf->getTodaySerial();
        $nowtime_serial = $cf->getNowtimeSerial();
        
        // モデルのインスタンス化
        $model_timestamp = new t001_web_punch_clock();
        // 社員ID,打刻年月日、出退勤ID、打刻時間、をDBへ登録
        $is_success = $model_timestamp->insertWebPunchClock($employee_id, $today_serial, $clocking_in_out_id, $nowtime_serial);
        if(!$is_success)
        {
            //インサートのエラー
            return response()->json([
                'result' => false,
                'values' => [
                    'error' => '2',
                    'message' => 'DB error'
                ]
            ]);
        }
        //インサート結果として必要な情報を渡す
        $model_employee_id_name = new m007_employee();
        $employee_info = $model_employee_id_name->getEmployeeData($employee_id);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        Log::info(3, $clocking_in_out_id, "打刻実施(". $user_agent . ")", $employee_id);

        return response()->json([
            'result'  => true,
            'values' => [
                'employee_code' => $employee_info->employee_code,
                'employee_name' => $employee_info->employee_name,
                'clocking_in_out_id' => $clocking_in_out_id,
                'web_punched_date' => $today_serial,
                'web_punched_time' => $nowtime_serial,
            ]
        ]);
    }
    /**
     * Web打刻画面のキープアライブ
     */
    public function keepAlive()
    {
        //200のOKレスポンス返せばなんでもOK
        return response()->json([
            'result' => true
        ]);
    }
    /**
     * 動作検証用
     * リリース時に削除
     */
    public function test_model()
    {
        Log::info(0, 0, "ログテスト", 0);
        // $information = \App\Models\m002_information::find(1);
        // $information->information_type;
        // $office = \App\Models\m004_office::find(1);
        // $office->information;
        // $m007_employee = \App\Models\m007_employee::find(1);
     
        return response()->json([
            'result'  => true,
            'values' => [
                // 'information' => $information,
                // 'office' => $office,
                // 'm007_employee' => $m007_employee,
                'success'=> "success"
            ]
        ]);   
    }
}
