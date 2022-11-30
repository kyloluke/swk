<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class m007_employee extends Model
{
    use HasFactory;
    // テーブルの関連付け
    protected $table = 'm007_employee';
    // 更新可能な項目の設定
    protected $fillable = [
            'employee_code',
            'company_id',
            'office_id',
            'dept_id',
            'work_closing_belonging_office_id',
            'post_id',
            'employee_name',
            'employee_kana_name',
            'gender',
            'joined_company_date',
            'retirement_company_date',
            'calendar_id',
            'personal_calendar_id',
            'work_zone_id',
            'week_scheduled_working_days',
            'scheduled_working_hours',
            'overtime_base_time',
            'available_input_class',
            'thirtysix_agreement_apply_id',
            'employment_style_id',
            'close_date_id',
            'grant_paid_leave_pattern_id',
            'authority_pattern_id',
            'first_paid_leave_date',
            'grant_paid_leave_type_id',
            'stamping_target_class',
            'email_address',
            'stamping_password',
            'grant_starting_date',
            'work_management_target_class',
            'valid_date_start',
            'valid_date_end',
            'detail_no',
            'field_work',
            'deviation_time_before_start_time_id',
            'deviation_time_after_end_time_id',
            'is_delete',
            'created_user',
            'updated_user'
    ];
    protected $primaryKey = "employee_id";

    public function getData()
    {
        $m007Employee = DB::table($this->table)->get();

        return $m007Employee;
    }
    public function company()
    {
        return $this->belongsTo('App\Models\m003_company', 'company_id');
    }
    public function office()
    {
        return $this->belongsTo('App\Models\m004_office', 'office_id');
    }
    public function dept()
    {
        return $this->belongsTo('App\Models\m005_dept', 'dept_id');
    }
    public function post()
    {
        return $this->belongsTo('App\Models\m006_post', 'post_id');
    }
    public function calendar()
    {
        return $this->belongsTo('App\Models\m021_calendar', 'calendar_id');
    }
    public function personal_calendar()
    {
        return $this->belongsTo('App\Models\m021_calendar', 'personal_calendar_id');
    }
    public function work_zone()
    {
        return $this->belongsTo('App\Models\m023_work_zone', 'work_zone_id');
    }
    public function thirtysix_agreement_apply()
    {
        return $this->belongsTo('App\Models\m040_36agreement_apply', 'thirtysix_agreement_apply_id');
    }
    public function employment_style()
    {
        return $this->belongsTo('App\Models\m013_employment_style', 'employment_style_id');
    }
    public function close_date()
    {
        return $this->belongsTo('App\Models\m016_close_date', 'close_date_id');
    }
    public function grant_paid_leave_pattern()
    {
        return $this->belongsTo('App\Models\m033_grant_paid_leave_pattern', 'grant_paid_leave_pattern_id');
    }
    public function authority_pattern()
    {
        return $this->belongsTo('App\Models\m011_authority_pattern', 'authority_pattern_id');
    }
    public function deviation_time_after_end_time()
    {
        return $this->hasOne('App\Models\m028_web_punch_clock_deviation_time', 'web_punch_clock_deviation_time_id', 'deviation_time_after_end_time_id');
    }
    public function deviation_time_before_start_time()
    {
        return $this->hasOne('App\Models\m028_web_punch_clock_deviation_time', 'web_punch_clock_deviation_time_id', 'deviation_time_before_start_time_id');
    }

    /**
     * m007_employeeテーブルの全ユーザーのパスワードをデフォルト（employee_code）へ変更する
     */
    public function setDefaultPasswordAll()
    {
        $employee_code_array = DB::table($this->table)
            ->select('employee_code')
            ->where('is_delete', 0)
            ->get();
        foreach($employee_code_array as $employee_code)
        {
            $this->setDefaultPassword($employee_code -> employee_code);
        }
        return true;
    }
    /**
     * 指定したemployee_idのユーザーのパスワードをデフォルトへ変更する
     */
    public function setDefaultPassword($employee_id)
    {
        $selected = DB::table($this->table)
            ->select('employee_code')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
        $hash = Hash::make($selected->employee_code);

        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->update([
                'stamping_password' => $hash
            ]);
        return true;
    }
    /**
     * 指定したidのユーザーのパスワードを指定の文字列に変更する
     */
    public function setPassword($employee_id, $password)
    {
        //ハッシュ化してUpdate
        $hash = Hash::make($password);
        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->update([
                'stamping_password' => $hash
            ]);
        //UserテーブルをUpsert
        $employee = DB::table($this->table)
            -> select('employee_id', 'company_id', 'employee_name', 'stamping_password')
            -> where('employee_id', $employee_id)
            -> where('is_delete', 0)
            -> first();
        DB::table('users')
            -> updateOrInsert(
                [
                    'employee_id' => $employee_id
                ],
                [
                    'employee_id' => $employee->employee_id,
                    'name' => $employee->employee_name,
                    'password' => $employee->stamping_password,
                    'company_id' => $employee->company_id,
                ]
        );
        return true;
    }
    /**
     * m007_employeeテーブルのユーザー情報をusersテーブルへemployee_idをキーにアップサートする
     */
    public function copyUsers()
    {
        //全データ取得
        //連携データはemployee_idと名前のみ（とりあえず）
        $employees = DB::table($this->table)
            -> select('employee_id', 'employee_name', 'stamping_password', 'company_id')
            -> where('is_delete', 0)
            -> get();

        //Usersテーブルへアップサート
        foreach($employees as $employee)
        {
            DB::table('users')
                -> updateOrInsert(
                    [
                        'employee_id' => $employee->employee_id
                    ],
                    [
                        'employee_id' => $employee->employee_id,
                        'name' => $employee->employee_name,
                        'password' => $employee->stamping_password,
                        'company_id' => $employee->company_id,
                    ]
            );
        }
    }
    /**
     * employee_codeとcompany_idからemployee_idを特定する
     */
    public function getEmployeeID($company_id, $employee_code)
    {
        if($company_id === null || $employee_code === null)
        {
            return [
                'result' => false,
                'employee_id' => null,
            ];
        }
        $employee_id = DB::table($this->table)
            ->select('employee_id')
            ->where('company_id', $company_id)
            ->where('employee_code', $employee_code)
            ->where('is_delete', 0)
            ->first();
        if($employee_id === null)
        {
            return [
                'result' => false,
                'employee_id' => null,
            ];
        }
        else
        {
            return [
                'result' => true,
                'employee_id' => $employee_id->employee_id,
            ];
        }
    }
    /**
     * 社員IDから社員情報をすべて取得
     * パスワード等不要なデータは削除してから渡す
     */
    public function getEmployeeData($employee_id)
    {
        //全データ取得
        $employee_data = DB::table($this->table)
            ->select(
                'employee_id',
                'employee_code',
                'company_id',
                'office_id',
                'dept_id',
                'work_closing_belonging_office_id',
                'post_id',
                'employee_name',
                'employee_kana_name',
                'gender',
                'joined_company_date',
                'retirement_company_date',
                'calendar_id',
                'personal_calendar_id',
                'work_zone_id',
                'week_scheduled_working_days',
                'scheduled_working_hours',
                'overtime_base_time',
                'available_input_class',
                'employment_style_id',
                'close_date_id',
                'grant_paid_leave_pattern_id',
                'authority_pattern_id',
                'first_paid_leave_date',
                'grant_paid_leave_type_id',
                'stamping_target_class',
                'email_address',
                'grant_starting_date',
                'work_management_target_class',
                'valid_date_start',
                'valid_date_end',
                'detail_no',
                'is_delete',
                'thirtysix_agreement_apply_id'
            )
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
        
        return $employee_data;
    }
    /**
     * 社員コード、会社IDより社員IDと氏名を取得 (is_deleteは0が登録されているもの)
     */
    public function getEmployeeIdName($employee_code, $company_id)        
    {
        $employeeIdNameInfo = DB::table($this->table)
            ->select('employee_id', 'employee_name')
            ->where('employee_code', $employee_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->first();

        return $employeeIdNameInfo;
    }
    
    /**
     * 社員コード、会社IDより社員IDと氏名を取得 (管理者以外、is_deleteは0が登録されているもの)
     */
    public function getEmployeeIdNameNoAdmin($employee_code, $company_id)        
    {
        $employeeIdNameInfo = DB::table($this->table)
            ->select('employee_id', 'employee_name')
            ->where('employee_code', $employee_code)
            ->where('company_id', $company_id)
            ->where('work_management_target_class','!=',0)
            ->where('is_delete', 0)
            ->first();

        return $employeeIdNameInfo;
    }

    /**
     * 自身の事業所の社員コード、会社IDより社員IDと氏名を取得 (管理者以外、is_deleteは0が登録されているもの)
     */
    public function getEmployeeIdNameOnlyOffice($employee_code, $company_id, $office_id)        
    {
        $employeeIdNameInfo = DB::table($this->table)
            ->select('employee_id', 'employee_name')
            ->where('employee_code', $employee_code)
            ->where('company_id', $company_id)
            ->where('office_id', $office_id)
            ->where('work_management_target_class','!=',0)
            ->where('is_delete', 0)
            ->first();

        return $employeeIdNameInfo;
    }

    /**
     * 社員コード、会社IDより社員データを取得 (is_deleteは0が登録されているもの)
     */
    public function getEmployeeAll($employee_code, $company_id)        
    {
        $employeeIdNameInfo = DB::table($this->table)
            ->where('employee_code', $employee_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->first();

        return $employeeIdNameInfo;
    }
    /**
     * 社員IDをキーにハッシュ値を取得
     */
    public function getHash($employee_id)
    {
        $employeeIdNameInfo = DB::table($this->table)
            ->select('stamping_password')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
        return $employeeIdNameInfo;
    }

    /**
     * 社員IDから社員名を取得
     */
    public function getNameByID($employee_id)
    {
        return DB::table($this->table)
            ->select('employee_name')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
    }
    /**
     * 社員IDから社員ID,社員code,社員名,役職,事業所,部署を取得
     */
    public function getSimpleEmployeeDataByID($employee_id)
    {
        return DB::table($this->table)
            ->select('employee_id','employee_code','employee_name','post_id','office_id','dept_id')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
    }
    /**
     * 社員IDから社員ID,社員code,社員名,役職,事業所,部署,締め区分を取得
     */
    public function getSimpleEmployeeDataandCloseDateByID($employee_id)
    {
        return DB::table($this->table)
            ->select('employee_id','employee_code','employee_name','post_id','office_id','dept_id','close_date_id')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
    }

    /**
     * パスワード更新、そしてuserテーブルにコピー
     */
    public function updatePasswordAndCopyToUser($employee_id, $stamping_password)
    {
        $hash_password = Hash::make($stamping_password);
        DB::table($this->table)
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->update(['stamping_password' => $hash_password]);

        DB::table('users')
            ->where('employee_id', $employee_id)
            ->update(['password' => $hash_password]);

    }
    /**
     * 削除されていない全会社の全社員選択
     */
    public function getAllEmployeeID()
    {
        return DB::table($this->table)
            ->where('is_delete', 0)
            ->get();
    }
    /**
     * 会社所属の全社員選択
     */
    public function getAllEmployeeBelongCompany($company_id)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->orderBy('employee_id')
            ->get();
    }
    /**
     * 会社所属の全社員選択（管理者以外）
     */
    public function getAllEmployeeBelongCompanyNoAdmin($company_id)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->where('work_management_target_class','!=',0)
            ->orderBy('employee_id')
            ->get();
    }
    /**
     * 会社所属の全社員選択、ID=1-20オペレーターを除く
     */
    public function getAllEmployeeBelongCompanyExceptOperator($company_id)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('employee_id', '>', 20)
            ->where('is_delete', 0)
            ->orderBy('employee_id')
            ->get();
    }
    /**
     * 締め日と会社ID指定での全社員取得
     */
    public function getAllEmployeeBelongCompanyWithCloseDate($company_id, $close_date_id, $today_serial)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('close_date_id', $close_date_id)
            ->where('is_delete', 0)
            ->where('work_management_target_class','!=',0)
            ->where('retirement_company_date','>=',$today_serial)
            ->get();
    }
    /**
     * 会社所属、対象締め区分の全社員選択(管理者、退職者は対象外。退職者は退職月は残る)
     */
    public function getAllEmployeeBelongCompanyByCloseID($company_id, $close_date_id, $target_end_serial)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('close_date_id', $close_date_id)
            ->where('is_delete', 0)
            ->where('work_management_target_class','!=',0)
            ->where('retirement_company_date','>=',$target_end_serial)
            ->get();
    }
    /**
     * 社員IDからoffice_idを取得
     */
    public function getOfficeIdByID($employee_id)
    {
        return DB::table($this->table)
            ->select('office_id')
            ->where('employee_id', $employee_id)
            ->where('is_delete', 0)
            ->first();
    }
    /**
     * 対象事業所の全社員選択
     */
    public function getAllEmployeeBelongOffice($office_id)
    {
        return DB::table($this->table)
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 対象事業所の全社員選択(管理者以外)
     */
    public function getAllEmployeeBelongOfficeNoAdmin($office_id)
    {
        return DB::table($this->table)
            ->where('office_id', $office_id)
            ->where('is_delete', 0)
            ->where('work_management_target_class','!=',0)
            ->get();
    }

    /**
     * 社員コード、会社IDより社員IDを取得 (is_deleteは0が登録されているもの)
     */
    public function getIdByCode($employee_code, $company_id)        
    {
        return DB::table($this->table)
            ->select('employee_id')
            ->where('employee_code', $employee_code)
            ->where('company_id', $company_id)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * 会社所属の全社員選択
     */
    public function getAllEmployeeBelongCompanyWithinTerm($company_id,$today_serial)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->where('valid_date_start', '<=', $today_serial)
            ->where('valid_date_end', '>=', $today_serial)
            ->where('is_delete', 0)
            ->get();
    }

    /**
     * データをインサートします
     */
    public function setEmployeeData(
        $employee_id,
        $employee_code,
        $company_id,
        $employee_name,
        $employee_kana_name,
        $gender,
        $joined_company_date,
        $retirement_company_date,
        $calendar_id,
        $personal_calendar_id,
        $work_zone_id,
        $week_scheduled_working_days,
        $scheduled_working_hours,
        $overtime_base_time,
        $available_input_class,
        $close_date_id,
        $grant_paid_leave_pattern_id,
        $authority_pattern_id,
        $first_paid_leave_date,
        $stamping_target_class,
        $email_address,
        $grant_starting_date,
        $work_management_target_class,
        $is_delete,
        $thirtysix_agreement_apply_id,
        $date,
        $userCode)
    {
        //全データインサート
        DB::table($this->table)
        ->updateOrInsert(
            ['employee_id' => $employee_id],
            ['employee_id' => $employee_id, 
            'employee_code' => $employee_code, 
            'company_id' => $company_id, 
            'employee_name' => $employee_name, 
            'employee_kana_name' => $employee_kana_name, 
            'gender' => $gender, 
            'joined_company_date' => $joined_company_date, 
            'retirement_company_date' => $retirement_company_date, 
            'calendar_id' => $calendar_id, 
            'personal_calendar_id' => $personal_calendar_id, 
            'work_zone_id' => $work_zone_id, 
            'week_scheduled_working_days' => $week_scheduled_working_days, 
            'scheduled_working_hours' => $scheduled_working_hours, 
            'overtime_base_time' => $overtime_base_time, 
            'available_input_class' => $available_input_class, 
            'close_date_id' => $close_date_id, 
            'grant_paid_leave_pattern_id' => $grant_paid_leave_pattern_id, 
            'authority_pattern_id' => $authority_pattern_id, 
            'first_paid_leave_date' => $first_paid_leave_date, 
            'stamping_target_class' => $stamping_target_class, 
            'email_address' => $email_address, 
            'grant_starting_date' => $grant_starting_date, 
            'work_management_target_class' => $work_management_target_class, 
            'valid_date_start' => 0, 
            'valid_date_end' => 2958465, 
            'detail_no' => $employee_id,
            'is_delete' => $is_delete, 
            'thirtysix_agreement_apply_id' => $thirtysix_agreement_apply_id, 
            'created_user' => $userCode, 
            'created_at' => $date, 
            'updated_user' => $userCode,
            'updated_at' => $date]
        );
    }

    /**
     * データをインサートします
     */
    public function insertEmployeeData(
        $employee_code,
        $company_id,
        $office_id,
        $dept_id,
        $work_closing_belonging_office_id,
        $post_id,
        $employee_name,
        $employee_kana_name,
        $gender,
        $joined_company_date,
        $retirement_company_date,
        $personal_calendar_id,
        $work_zone_id,
        $week_scheduled_working_days,
        $scheduled_working_hours,
        $overtime_base_time,
        $available_input_class,
        $employment_style_id,
        $close_date_id,
        $authority_pattern_id,
        $first_paid_leave_date,
        $stamping_target_class,
        $email_address,
        $grant_starting_date,
        $work_management_target_class,
        $thirtysix_agreement_apply_id,
        $grant_paid_leave_type_id,
        $field_work,
        $deviation_time_before_start_time_id,
        $deviation_time_after_end_time_id,
        $valid_date_start,
        $valid_date_end,
        $detail_no,
        $date,
        $userCode)
    {
        //全データインサート
        return DB::table($this->table)->insert(
            ['employee_code' => $employee_code, 
            'company_id' => $company_id, 
            'office_id' => $office_id, 
            'dept_id' => $dept_id, 
            'work_closing_belonging_office_id' => $work_closing_belonging_office_id, 
            'post_id' => $post_id, 
            'employee_name' => $employee_name, 
            'employee_kana_name' => $employee_kana_name, 
            'gender' => $gender, 
            'joined_company_date' => $joined_company_date, 
            'retirement_company_date' => $retirement_company_date, 
            'calendar_id' => 1, 
            'personal_calendar_id' => $personal_calendar_id, 
            'work_zone_id' => $work_zone_id, 
            'week_scheduled_working_days' => $week_scheduled_working_days, 
            'scheduled_working_hours' => $scheduled_working_hours, 
            'overtime_base_time' => $overtime_base_time, 
            'available_input_class' => $available_input_class, 
            'employment_style_id' => $employment_style_id, 
            'close_date_id' => $close_date_id, 
            'grant_paid_leave_pattern_id' => 1, 
            'authority_pattern_id' => $authority_pattern_id, 
            'first_paid_leave_date' => $first_paid_leave_date, 
            'stamping_target_class' => $stamping_target_class, 
            'email_address' => $email_address, 
            'grant_starting_date' => $grant_starting_date, 
            'work_management_target_class' => $work_management_target_class, 
            'valid_date_start' => $valid_date_start, 
            'valid_date_end' => $valid_date_end,
            'detail_no' => $detail_no,
            'is_delete' => 0, 
            'thirtysix_agreement_apply_id' => $thirtysix_agreement_apply_id, 
            'grant_paid_leave_type_id' => $grant_paid_leave_type_id,
            'field_work' => $field_work,
            'deviation_time_before_start_time_id' => $deviation_time_before_start_time_id,
            'deviation_time_after_end_time_id' => $deviation_time_after_end_time_id,
            'created_user' => $userCode, 
            'created_at' => $date, 
            'updated_user' => $userCode,
            'updated_at' => $date]
        );
    }

        /**
     * データをインサートします
     */
    public function updateEmployeeData(
        $employee_id,
        $employee_code,
        $company_id,
        $employee_name,
        $employee_kana_name,
        $gender,
        $joined_company_date,
        $retirement_company_date,
        $personal_calendar_id,
        $work_zone_id,
        $week_scheduled_working_days,
        $scheduled_working_hours,
        $overtime_base_time,
        $available_input_class,
        $close_date_id,
        $authority_pattern_id,
        $first_paid_leave_date,
        $stamping_target_class,
        $email_address,
        $grant_starting_date,
        $work_management_target_class,
        $thirtysix_agreement_apply_id,
        $work_closing_belonging_office_id,
        $office_id,
        $grant_paid_leave_type_id,
        $field_work,
        $deviation_time_before_start_time_id,
        $deviation_time_after_end_time_id,
        $valid_date_start,
        $valid_date_end,
        $detail_no,
        $date,
        $userCode)
    {
        //全データインサート
        DB::table($this->table)
        ->updateOrInsert(
            ['employee_id' => $employee_id],
            ['employee_code' => $employee_code, 
            'company_id' => $company_id, 
            'employee_name' => $employee_name, 
            'employee_kana_name' => $employee_kana_name, 
            'gender' => $gender, 
            'joined_company_date' => $joined_company_date, 
            'retirement_company_date' => $retirement_company_date, 
            'calendar_id' => 1, 
            'personal_calendar_id' => $personal_calendar_id, 
            'work_zone_id' => $work_zone_id, 
            'week_scheduled_working_days' => $week_scheduled_working_days, 
            'scheduled_working_hours' => $scheduled_working_hours, 
            'overtime_base_time' => $overtime_base_time, 
            'available_input_class' => $available_input_class, 
            'close_date_id' => $close_date_id, 
            'grant_paid_leave_pattern_id' => 1, 
            'authority_pattern_id' => $authority_pattern_id, 
            'first_paid_leave_date' => $first_paid_leave_date, 
            'stamping_target_class' => $stamping_target_class, 
            'email_address' => $email_address, 
            'grant_starting_date' => $grant_starting_date, 
            'work_management_target_class' => $work_management_target_class, 
            'valid_date_start' => $valid_date_start, 
            'valid_date_end' => $valid_date_end,
            'is_delete' => 0, 
            'office_id' => $office_id,
            'field_work' => $field_work,
            'deviation_time_before_start_time_id' => $deviation_time_before_start_time_id,
            'deviation_time_after_end_time_id' => $deviation_time_after_end_time_id,
            'work_closing_belonging_office_id' => $work_closing_belonging_office_id,
            'thirtysix_agreement_apply_id' => $thirtysix_agreement_apply_id, 
            'grant_paid_leave_type_id' => $grant_paid_leave_type_id,
            'created_user' => $userCode, 
            'created_at' => $date, 
            'updated_user' => $userCode,
            'updated_at' => $date]
        );
    }
    /**
     * 一番大きい数のdetail_no取得
     */
    public function last_detail_no()
    {
        return DB::table($this->table)
            ->select('detail_no')
            ->orderBy('detail_no', 'desc')
            ->first();
    }

    /**
     * 対象社員の締め状態ID更新
     */
    public function updateIsDelete($targetEmployeeID,$userCode,$update_date)
    {
        return DB::table($this->table)
            ->where('employee_id', $targetEmployeeID)
            ->where('is_delete', 0)
            ->update([
                'is_delete' => 1, 
                'updated_user' => $userCode,
                'updated_at' => $update_date
        ]);
    }
    /**
    * employee_codeとcompany_idからemployee_idを特定する
    */
    public function getEmployeeFromGeneral($employee_code,$office_id,$company_id)
    {
       if($employee_code == "" && $office_id == 0){
           return DB::table($this->table)
           ->select(
               'employee_id',
               'employee_code',
               'company_id',
               'office_id',
               'dept_id',
               'work_closing_belonging_office_id',
               'post_id',
               'employee_name',
               'employee_kana_name',
               'gender',
               'joined_company_date',
               'retirement_company_date',
               'calendar_id',
               'personal_calendar_id',
               'work_zone_id',
               'week_scheduled_working_days',
               'scheduled_working_hours',
               'overtime_base_time',
               'available_input_class',
               'employment_style_id',
               'close_date_id',
               'grant_paid_leave_pattern_id',
               'authority_pattern_id',
               'first_paid_leave_date',
               'stamping_target_class',
               'email_address',
               'grant_starting_date',
               'work_management_target_class',
               'valid_date_start',
               'valid_date_end',
               'detail_no',
               'is_delete',
               'thirtysix_agreement_apply_id'
           )
           ->where('company_id', $company_id)
           ->where('is_delete', 0)
           ->get();
       }else if($employee_code != "" && $office_id == 0){
           return DB::table($this->table)
           ->select(
               'employee_id',
               'employee_code',
               'company_id',
               'office_id',
               'dept_id',
               'work_closing_belonging_office_id',
               'post_id',
               'employee_name',
               'employee_kana_name',
               'gender',
               'joined_company_date',
               'retirement_company_date',
               'calendar_id',
               'personal_calendar_id',
               'work_zone_id',
               'week_scheduled_working_days',
               'scheduled_working_hours',
               'overtime_base_time',
               'available_input_class',
               'employment_style_id',
               'close_date_id',
               'grant_paid_leave_pattern_id',
               'authority_pattern_id',
               'first_paid_leave_date',
               'stamping_target_class',
               'email_address',
               'grant_starting_date',
               'work_management_target_class',
               'valid_date_start',
               'valid_date_end',
               'detail_no',
               'is_delete',
               'thirtysix_agreement_apply_id'
           )
           ->where('company_id', $company_id)
           ->where('employee_code', $employee_code)
           ->where('is_delete', 0)
           ->get();
       }else if($employee_code == "" && $office_id != 0){
           return DB::table($this->table)
           ->select(
               'employee_id',
               'employee_code',
               'company_id',
               'office_id',
               'dept_id',
               'work_closing_belonging_office_id',
               'post_id',
               'employee_name',
               'employee_kana_name',
               'gender',
               'joined_company_date',
               'retirement_company_date',
               'calendar_id',
               'personal_calendar_id',
               'work_zone_id',
               'week_scheduled_working_days',
               'scheduled_working_hours',
               'overtime_base_time',
               'available_input_class',
               'employment_style_id',
               'close_date_id',
               'grant_paid_leave_pattern_id',
               'authority_pattern_id',
               'first_paid_leave_date',
               'stamping_target_class',
               'email_address',
               'grant_starting_date',
               'work_management_target_class',
               'valid_date_start',
               'valid_date_end',
               'detail_no',
               'is_delete',
               'thirtysix_agreement_apply_id'
           )
           ->where('company_id', $company_id)
           ->where('office_id', $office_id)
           ->where('is_delete', 0)
           ->get();
       }else{
           return DB::table($this->table)
           ->select(
               'employee_id',
               'employee_code',
               'company_id',
               'office_id',
               'dept_id',
               'work_closing_belonging_office_id',
               'post_id',
               'employee_name',
               'employee_kana_name',
               'gender',
               'joined_company_date',
               'retirement_company_date',
               'calendar_id',
               'personal_calendar_id',
               'work_zone_id',
               'week_scheduled_working_days',
               'scheduled_working_hours',
               'overtime_base_time',
               'available_input_class',
               'employment_style_id',
               'close_date_id',
               'grant_paid_leave_pattern_id',
               'authority_pattern_id',
               'first_paid_leave_date',
               'stamping_target_class',
               'email_address',
               'grant_starting_date',
               'work_management_target_class',
               'valid_date_start',
               'valid_date_end',
               'detail_no',
               'is_delete',
               'thirtysix_agreement_apply_id'
           )
           ->where('company_id', $company_id)
           ->where('employee_code', $employee_code)
           ->where('office_id', $office_id)
           ->where('is_delete', 0)
           ->get();
       }    
    }
    /**
     * 対象社員の退職年月日と有効年月日を更新
     */
    public function updateIsRetirementDay($targetEmployeeID,$retirementDaySerial,$userCode)
    {
        return DB::table($this->table)
            ->where('employee_id', $targetEmployeeID)
            ->where('is_delete', 0)
            ->update([
                'retirement_company_date' => $retirementDaySerial,
                'valid_date_end' => $retirementDaySerial,
                'updated_user' => $userCode,
        ]);
    }
    /**
     * 初期社員を作成
     *
     * @param [type] $employee_code
     * @param [type] $company_id
     * @param [type] $office_id
     * @param [type] $dept_id
     * @param [type] $work_closing_belonging_office_id
     * @param [type] $post_id
     * @param [type] $employee_name
     * @param [type] $employee_kana_name
     * @param [type] $gender
     * @param [type] $joined_company_date
     * @param [type] $retirement_company_date
     * @param [type] $personal_calendar_id
     * @param [type] $work_zone_id
     * @param [type] $week_scheduled_working_days
     * @param [type] $scheduled_working_hours
     * @param [type] $overtime_base_time
     * @param [type] $available_input_class
     * @param [type] $employment_style_id
     * @param [type] $close_date_id
     * @param [type] $authority_pattern_id
     * @param [type] $first_paid_leave_date
     * @param [type] $stamping_target_class
     * @param [type] $email_address
     * @param [type] $grant_starting_date
     * @param [type] $work_management_target_class
     * @param [type] $thirtysix_agreement_apply_id
     * @param [type] $field_work
     * @param [type] $deviation_time_before_start_time_id
     * @param [type] $deviation_time_after_end_time_id
     * @param [type] $valid_date_start
     * @param [type] $valid_date_end
     * @param [type] $detail_no
     * @param [type] $date
     * @param [type] $userCode
     * @return void
     */
    public function createDefaultEmployee($employee_code, $company_id, $office_id, $employee_name, $employee_password)
    {
        $employee_id = DB::table($this->table)->insertGetId([
            'employee_code' => $employee_code, 
            'company_id' => $company_id, 
            'office_id' => $office_id, 
            'work_closing_belonging_office_id' => $office_id, 
            'employee_name' => $employee_name, 
            'joined_company_date' => 0, 
            'retirement_company_date' => 0,
            'scheduled_working_hours' => 0,
            'overtime_base_time' => 0,
            'available_input_class' => 0,
            'close_date_id' => 1,
            'first_paid_leave_date' => 0,
            'stamping_target_class' => 0,
            'work_management_target_class' => 0,
            'calendar_id' => 1,
            'personal_calendar_id' => 0,
            'authority_pattern_id' => 16,
            'valid_date_start' => 0, 
            'valid_date_end' => 2958465,
            ]
        );
        $this->setPassword($employee_id, $employee_password);
        return true;
    }
    /**
     * 企業管理者を取得
     * 暫定措置として一番小さいIDの人
     *
     * @param int $company_id
     * @return m007_employee
     */
    public function getDefaultEmployee($company_id)
    {
        return DB::table($this->table)
            ->where('company_id', $company_id)
            ->orderBy('employee_id')
            ->first();
    }
}
