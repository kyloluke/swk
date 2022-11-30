<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\m001_information_type;
use App\Models\m004_office;
use App\Models\m005_dept;
use App\Models\m006_post;
use App\Models\m007_employee;
use App\Models\m009_display_color;
use App\Models\m010_message;
use App\Models\m013_employment_style;
use App\Models\m014_over_time_class;
use App\Models\m015_deduction_reason;
use App\Models\m016_close_date;
use App\Models\m017_rest_time;
use App\Models\m018_approval_state;
use App\Models\m019_close_state;
use App\Models\m021_calendar;
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
use App\Models\m046_grant_paid_leave_type;
use App\Http\AppLibs\CommonFunctions;
use App\Http\AppLibs\EmployeeInfoFunctions;
use App\Models\m028_web_punch_clock_deviation_time;

class ShachihataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function modelapp(Request $request)
    {
        $cf = new CommonFunctions();

        $user_id = Auth::id();
        // 社員情報の取得
        //取得用employeeオブジェクト
        $employee_data = m007_employee::find($user_id);

        //事業所、勤怠締め所属事業所、役職、勤務形態
        $today_serial = $cf->getTodaySerial();
        $employee_func = new EmployeeInfoFunctions();
        $employee_data = $employee_func->getEmployeeInfo($user_id, $today_serial);

        //会社
        $employee_data->company;
        //勤務帯
        $employee_data->office->work_zone;
        //所属ツリー（配列）
        $mdm005Dept = new m005_dept();
        $m005DeptTree = "";
        $dept = $employee_data->dept;
        if($dept)
        {
            $m005DeptTree = $mdm005Dept->getNameTree($employee_data->dept->dept_id);
        }
        $employee_data['dept_tree'] = $m005DeptTree;
        //カレンダ
        $employee_data->calendar;
        //個人カレンダ
        $employee_data->personal_calendar;
        //勤務帯
        $employee_data->work_zone;
        //36条項適用
        $employee_data->thirtysix_agreement_apply;
        //締め日
        $employee_data->close_date;
        //有休付与パターン
        $employee_data->grant_paid_leave_pattern;
        //権限パターン
        $employee_data->authority_pattern;
        
        //表示用マスタの取得　※マルチテナント時は会社内の値取得
        //セレクトボックスなどに初めから表示するような値はSessionへ保持
        $m030_work_achievement = new m030_work_achievement();
        $master = array();
        $master['display_color'] = m009_display_color::all();
        $master['message'] = m010_message::all();
        $master['overtime_class'] = m014_over_time_class::all();
        $master['deduction_reason'] = m015_deduction_reason::all();
        $master['rest_time'] = m017_rest_time::all();
        $master['approval_state'] = m018_approval_state::all();
        $master['close_state'] = m019_close_state::all();
        $clocking_in_out = m025_clocking_in_out::all();
        foreach($clocking_in_out as $elm)
        {
            $elm->web_punch_clock_deviation_time;
        }
        $master['clocking_in_out'] = $clocking_in_out;
        $master['work_holiday'] = m027_work_holiday::all();
        $master['web_punch_clock_deviation_time'] = m028_web_punch_clock_deviation_time::all();
        $master['theme'] = m029_theme::all();
        $master['work_achievement'] = $m030_work_achievement->getData();
        $master['unemployed'] = m031_unemployed::all();
        $master['violation_warning'] = m037_violation_warning::all();
        $master['violation_warning_type'] = m038_violation_warning_type::all();
        $master['prevention_overwork_check'] = m039_prevention_overwork_check::all();
        $master['thirtysix_agreement_apply'] = m040_36agreement_apply::all();
        $master['holiday'] = m043_holiday::all();
        
        //マスタデータとしての一覧
        $company_id = $employee_data->company->company_id;
        $master['company_id'] = $employee_data->company->company_id;
        $mdm001InformationType = new m001_information_type();
        $master['information_type'] = $mdm001InformationType->getInformationTypeList($company_id);

        $mdm004Office = new m004_office();
        $master['office'] = $mdm004Office->getOfficeList($company_id);

        $dept_list = $mdm005Dept->getDeptList($cf->getTodaySerial());
        $dept_tree_list = [];
        foreach($dept_list as $d_info)
        {
            //部署名取得
            $m005DeptTree = $mdm005Dept->getNameTree($d_info->dept_id);
            $dept_id = $d_info->dept_id;
            $office_id = $d_info->office_id;
            $dept_tree = implode('／', $m005DeptTree);
            $dept_tree_list[] = array(
                'dept_id' => $dept_id,
                'dept_name' => $dept_tree,
                'office_id' => $office_id,
            );
        }
        $master['dept'] = $dept_list;
        $master['dept_tree'] = $dept_tree_list;

        $mdm006Post = new m006_post();
        $master['post'] = $mdm006Post->getPostList($company_id);
        
        $mdm013EmploymentStyle = new m013_employment_style();
        $master['employment_style'] = $mdm013EmploymentStyle->getEmploymentStyleList();
        
        $mdm016CloseDate = new m016_close_date();
        $master['close_date'] = $mdm016CloseDate->getCloseDateList();
        
        $mdm021Calendar = new m021_calendar();
        $master['calendar'] = $mdm021Calendar->getCalendarList();

        $mdm023WorkZone = new m023_work_zone();
        $master['work_zone'] = $mdm023WorkZone->getWorkZoneList();

        $mdm024WorkZoneTime = new m024_work_zone_time();
        $master['work_zone_time'] = $mdm024WorkZoneTime->getValidList();

        $mdm040ThirtysixAgreementApply = new m040_36agreement_apply();
        $master['thirtysix_agreement_apply'] =  $mdm040ThirtysixAgreementApply->getThirtysixAgreementApplyList();

        $m046GrantPaidLeaveType = new m046_grant_paid_leave_type();
        $master['grant_paid_leave_type'] =  $m046GrantPaidLeaveType->getGrantPaidLeaveTypeList();

        $master['close_date_id'] =  $employee_data->close_date_id;

        //employee_dataに同居させる（bladeの制約回避）
        $employee_data['master_data'] = $master;
        
        //productionかどうかを保持
        $employee_data['is_production'] = config('app.env') == 'production';

        //マルチテナントかどうかを保持
        $employee_data['is_multitennant'] = env("IS_MULTI_TENNANT") == true;

        //セッションへ配置
        $request->session()->put('employee', $employee_data);
        
        //ログイン中の社員情報を取得
        //社員番号
        $employee_code = $employee_data->employee_code;
        //名前
        $employee_name = $employee_data->employee_name;
        //所属
        $office_name = $employee_data->office->office_name;

        // ビューを返す
        return view('app')->with([
            'employee_code' => $employee_code,
            'employee_name' => $employee_name,
            'office_name' => $office_name,
         ]);
    }
    public function setDefaultPassword()
    {
        $mdm007Employee = new m007_employee();
        //とりあえずID1
        return response()->json($mdm007Employee->setDefaultPasswordAll());
        //$m007Employeedata = $mdm007Employee->getDefaultPasswordAll();
    }

    public function copyUserInfo()
    {
        $mdm007Employee = new m007_employee();
        $mdm007Employee->copyUsers();
    }
    

    public function test_session(Request $request)
    {
        return response()->json($request->session()->all());
    }

    public function commonSearch(Request $request)
    {
        //ログイン中の社員情報を取得
        $employee_info = $request->session()->get('employee');
        $employee_id = $employee_info->employee_id;
        $company_id = $employee_info->company_id;
        $office_id = $employee_info->office_id;

        // モデルのインスタンス化 (事業所)
        $model_004_office = new m004_office();
        // モデルのインスタンス化 (部署)
        $model_005_dept = new m005_dept();
        // モデルのインスタンス化 (社員情報)
        $model_m007_employee = new m007_employee();

        // 事業所ID一覧取得
        $office_info = $model_004_office->getOfficeList($company_id);
        // 部署ID一覧取得
        $dept_info = $model_005_dept->getDepartmentList();

        $office_array = array();
        $dept_array = array();

        foreach($office_info as $o_info)
        {
            $office_id = $o_info->office_id;
            $office_name = $o_info->office_name;

            $office_array[] = array(
                'office_id' => $office_id,
                'office_name' => $office_name,
            );
        }
        foreach($dept_info as $d_info)
        {
            //部署名取得
            $m005DeptTree = $model_005_dept->getNameTree($d_info->dept_id);
            $dept_id = $d_info->dept_id;
            $dept_tree = implode('／', $m005DeptTree);

            $dept_array[] = array(
                'dept_id' => $dept_id,
                'dept_name' => $dept_tree,
            );
        }

        return response()->json([
            'result' => true,
            'office' => $office_array,
            'dept' => $dept_array,
            ]);
    }
    /**
     * 速度測定
     */
    public function testSpeed(Request $request){
        $time_start = microtime(true);

        
        
        $time = microtime(true) - $time_start;
        return response()->json([
            'time' => $time,
        ]);
    }
}
