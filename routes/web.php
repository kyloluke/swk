<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//シヤチハタ入口
Route::get('/swk', 'App\Http\Controllers\ApplicationRootController@appRoot')->name('swk');

//マルチテナント入口
Route::get('/kintai', 'App\Http\Controllers\ApplicationRootController@appRoot')->name('kintai');

//共通ルート
Route::get('/app', 'App\Http\Controllers\ShachihataController@modelapp');

//パスワード関連のrouter
//実体はAuth::routes()の先の、vendor/laravel/ui/src/AuthRouteMethods.php
Route::get('login', function () {
    return view('app');
})->name('login');
//ログインの実行
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');

//マスタデータ更新
Route::get('resetMasterData', 'App\Http\Controllers\MasterController@resetMasterData');
//ログイン時のEmployeeID取得
Route::post('get_id', 'App\Http\Controllers\UnAuthController@getEmployeeID');
Route::post('get_id_by_code', 'App\Http\Controllers\UnAuthController@getEmployeeIDByCode');
//インフォメーションの数取得
Route::post('getInformation', 'App\Http\Controllers\InformationController@getInformation');
//インフォメーション取得(会社全体)
Route::post('getAllInformation', 'App\Http\Controllers\InformationController@getAllInformation');
//Web打刻結果表示
Route::get('m103_record_time_result', 'App\Http\Controllers\UnAuthController@getTimestamp');
//Web打刻画面のキープアライブ
Route::get('keepAlive', 'App\Http\Controllers\UnAuthController@keepAlive');
//Web打刻時のパスワードチェック
Route::post('checkWebPunchPassword', 'App\Http\Controllers\UnAuthController@checkPassword');
//Web打刻実施
Route::post('execWebPunch', 'App\Http\Controllers\UnAuthController@execWebPunch');
//「対象者選択」の一覧表
Route::get('inputAgentList', 'App\Http\Controllers\AttendanceController@getInputAgentList');
//勤怠状況一覧
Route::get('approvalTargetList', 'App\Http\Controllers\TargetPersonnelController@getApprovalTargetList');
//労働・休暇状態確認画面のデータ取得
Route::get('getLaborSituationInfo', 'App\Http\Controllers\LaborSituationController@getLaborSituationInfo');
//勤怠情報取得（パネル表示）
Route::get('workingStatus', 'App\Http\Controllers\WorkingStatusController@getWorkingStatusMonthly');
//休暇情報取得
Route::get('getAbsentInfo', 'App\Http\Controllers\AbsentController@getAbsentInfo');
//対象社員／対象年月の全勤怠情報を取得
Route::get('getInputAttendanceInfo', 'App\Http\Controllers\InputAttendanceController@getInputAttendanceInfo');
//対象者一覧取得（代理・承認）
Route::get('get_setting_target', 'App\Http\Controllers\TargetPersonnelController@getTargetList');

//汎用検索状態チェック　※ポーリング処理のためログ除外
Route::get('generalSearchCheckState', 'App\Http\Controllers\GeneralSearchController@checkState');

//休暇情報取得（複数社員分）
Route::get('getAbsentInfoList', 'App\Http\Controllers\AbsentController@getAbsentInfoList');



Route::middleware(['controllerLog'])->group(function () {
    //マニュアルダウンロード
    Route::get('/manual', function () {
        return Storage::download('public/manual.pdf');
    });
    //日毎詳細モーダルマニュアルダウンロード
    Route::get('/achievement_manual', function () {
        return Storage::download('public/achievement_manual.pdf');
    });
    //インフォメーションを既読にする
    Route::post('readCheckedInformation', 'App\Http\Controllers\InformationController@readCheckedInformation');
    //インフォメーションを未読にする
    Route::post('unreadCheckedInformation', 'App\Http\Controllers\InformationController@unreadCheckedInformation');
    //インフォメーション編集
    Route::post('editInformation', 'App\Http\Controllers\InformationController@editInformation');
    //インフォメーション編集
    Route::post('deleteInformation', 'App\Http\Controllers\InformationController@deleteInformation');
    //カレンダ設定一覧
    Route::get('getCalendar', 'App\Http\Controllers\CalendarController@getCalendar');
    //祝日一覧
    Route::get('getHolidayList', 'App\Http\Controllers\CalendarController@getHolidayList');
    //カレンダ設定編集
    Route::post('editCalendar', 'App\Http\Controllers\CalendarController@editCalendar');
    //カレンダ設定新規作成
    Route::post('insertCalendar', 'App\Http\Controllers\CalendarController@insertCalendar');
    //カレンダ取得
    Route::get('getCalendarList', 'App\Http\Controllers\CalendarController@getCalendarList');
    //汎用検索
    Route::get('m110_common_search', 'App\Http\Controllers\ShachihataController@commonSearch');
    //パスワードチェックと変更
    Route::post('update_password', 'App\Http\Controllers\ModalController@checkUpdatePassword');
    //日毎詳細モーダル表示時のデータ取得
    Route::get('m105_input_attendance_detail', 'App\Http\Controllers\ModalController@getDailyInformation');
    //日毎詳細モーダルの登録（申請・承認・差戻）
    Route::post('m105_input_attendance_details_save', 'App\Http\Controllers\ModalController@saveDailyInformation');
    //日毎詳細モーダルの登録（一括申請）
    Route::post('m105_input_attendance_details_save_bunch', 'App\Http\Controllers\ModalController@saveBunchDailyInformation');
    //申請
    Route::post('informationAttendanceList_application', 'App\Http\Controllers\ModalController@informationAttendanceListApplication');
    //Web打刻一覧
    Route::get('m104_record_time_list', 'App\Http\Controllers\ModalController@getWebPunchList');
    //手入力打刻登録
    Route::post('insert_update_input_punches', 'App\Http\Controllers\ModalController@insertUpdateInputPunches');
    //当日手入力打刻登録
    Route::post('insert_update_today_input_punch', 'App\Http\Controllers\ModalController@insertUpdateTodayInputPunch');
    //チェックボックス一括承認
    Route::post('checked_attendance_details_approve', 'App\Http\Controllers\AttendanceController@approveCheckedAttendanceDetails');
    //出休変更一括登録
    Route::post('change_work_holiday', 'App\Http\Controllers\AttendanceController@changeWorkHoliday');
    //振替休日変更一括登録
    Route::post('change_substitute', 'App\Http\Controllers\AttendanceController@changeSubstitute');
    //対象社員日報一覧表
    Route::get('dailyReportList', 'App\Http\Controllers\InputAttendanceController@getDailyReportInfoList');
    //休日出勤者一覧
    Route::get('holidayWorkerInformationList', 'App\Http\Controllers\TargetPersonnelController@getHolidayWorkerInformationList');
    //36協定チェック一覧
    Route::get('thirtySixCheckList', 'App\Http\Controllers\TargetPersonnelController@getThirtySixCheckList');
    //有休・休日条件一覧
    Route::get('acquiredAndHolidayList', 'App\Http\Controllers\TargetPersonnelController@getAcquiredAndHolidayList');
    //対象者検索
    Route::get('officeTargetList', 'App\Http\Controllers\TargetPersonnelController@getOfficeTargetList');
    //承認対象者、代理入力者選択済み対象者取得
    Route::get('getSelectedTargetList', 'App\Http\Controllers\TargetPersonnelController@getSelectedTargetList');
    //承認対象者、代理入力者選択済み対象者登録
    Route::post('updateSelectedTargetList', 'App\Http\Controllers\TargetPersonnelController@updateSelectedTargetList');
    //乖離判定実施
    Route::get('update_violation_warning_id', 'App\Http\Controllers\AttendanceController@updateViolationWarningId');
    //ダウンロード機能
    Route::get('get_all_approved_list', 'App\Http\Controllers\DownloadController@getAllApprovedList');
    Route::get('get_all_agent_list', 'App\Http\Controllers\DownloadController@getAllAgentList');
    Route::get('get_all_employee_list', 'App\Http\Controllers\DownloadController@getAllEmployeeList');
    Route::get('get_unset_approved_list', 'App\Http\Controllers\DownloadController@getUnsetApprovedList');
    Route::get('get_unset_agent_list', 'App\Http\Controllers\DownloadController@getUnsetAgentList');
    Route::get('getSalaryAlignmentList', 'App\Http\Controllers\DownloadController@getSalaryAlignmentList');
    //アップロード機能
    Route::post('upload_all_approved_list', 'App\Http\Controllers\DownloadController@uploadAllApprovedList');
    Route::post('upload_all_agent_list', 'App\Http\Controllers\DownloadController@uploadAllAgentList');
    Route::post('upload_grant_paid_leave_list', 'App\Http\Controllers\DownloadController@uploadGrantPaidLeaveList');
    Route::post('upload_employee_information_list', 'App\Http\Controllers\DownloadController@uploadEmployeeInformationList');
    //アップロード前チェック
    Route::post('check_approver_agent', 'App\Http\Controllers\DownloadController@checkApproverAgent');
    Route::post('check_employee', 'App\Http\Controllers\DownloadController@checkEmployee');
    Route::post('check_grant_paid_leave', 'App\Http\Controllers\DownloadController@checkGrantPaidLeave');
    //休暇管理情報更新
    Route::post('update_holiday_management', 'App\Http\Controllers\AbsentController@updateHolidayManagement');
    //休暇管理情報作成
    Route::post('insert_holiday_management', 'App\Http\Controllers\AbsentController@insertHolidayManagement');
    //休暇情報取得（休暇付与モーダル）
    Route::get('m043_holiday_list', 'App\Http\Controllers\AbsentController@getHolidayList');
    //社員情報取得（マスタ画面）
    Route::get('getEmployeeInfo', 'App\Http\Controllers\MasterController@getEmployeeInformation');
    //社員情報新規作成
    Route::post('insert_employee_information', 'App\Http\Controllers\MasterController@insertEmployeeInformation');
    //社員情報更新
    Route::post('delete_employee_information', 'App\Http\Controllers\MasterController@deleteEmployeeInformation');
    //その他マスタデータの一覧表示
    Route::get('getOtherMasterList', 'App\Http\Controllers\MasterController@getOtherMasterList');
    //組織マスタデータの一覧表示
    Route::get('getOrganizationMasterList', 'App\Http\Controllers\MasterController@getOrganizationMasterList');
    //その他マスタデータの更新
    Route::post('updateOtherMasterData', 'App\Http\Controllers\MasterController@updateOtherMasterData');
    //組織マスタデータの更新
    Route::post('updateOrganizationMasterData', 'App\Http\Controllers\MasterController@updateOrganizationMasterData');
    //退職設定
    Route::get('retirement_employee', 'App\Http\Controllers\MasterController@retirementEmployee');
    //履歴更新
    Route::post('update_history_table', 'App\Http\Controllers\HistoryController@updateHistoryTable');
    Route::post('update_history_table_from_file', 'App\Http\Controllers\HistoryController@updateHistoryTableFromFlie');
    //本人締め
    Route::get('closeThemselves', 'App\Http\Controllers\ClosingController@closeThemselves');
    //管理者締め
    Route::get('closeManager', 'App\Http\Controllers\ClosingController@closeManager');
    //事業所締め状態取得
    Route::get('getOfficeClosingStatus', 'App\Http\Controllers\ClosingController@getOfficeClosingStatus');
    //事業所締め状態更新
    Route::get('updateOfficeClosingStatus', 'App\Http\Controllers\ClosingController@updateOfficeClosingStatus');
    //全社締め状態取得
    Route::get('getCompanyClosingStatus', 'App\Http\Controllers\ClosingController@getCompanyClosingStatus');
    //全社締め状態更新
    Route::get('updateCompanyClosingStatus', 'App\Http\Controllers\ClosingController@updateCompanyClosingStatus');
    //社員締め状態取得
    Route::get('getEmployeeClosingStatus', 'App\Http\Controllers\ClosingController@getEmployeeClosingStatus');
    //勤務帯編集
    Route::get('m023_edit', 'App\Http\Controllers\WorkZoneController@editWorkZone');
    //日報を取得
    Route::get('m106_get_daily_report', 'App\Http\Controllers\DailyReportController@getDailyReport');
    //日報を登録
    Route::post('m106_update_daily_report', 'App\Http\Controllers\DailyReportController@updateDailyReport');
    //ログアウトの実行
    Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
    //汎用検索のリスト取得
    Route::get('getGeneralSearchList', 'App\Http\Controllers\GeneralSearchController@getGeneralSearchList');
    //汎用検索実行
    Route::post('execGeneralSearchByDefineList', 'App\Http\Controllers\GeneralSearchController@execGeneralSearchByDefineList');
    //汎用検索結果ダウンロード
    Route::get('generalSearchDownloadResult', 'App\Http\Controllers\GeneralSearchController@downloadResult');
    //ダウンロードファイル生成
    Route::post('convertDownloadFile', 'App\Http\Controllers\GeneralSearchController@mekaDownloadFile');
    //ストリームダウンロード
    Route::post('downloadByStream', 'App\Http\Controllers\GeneralSearchController@downloadByStream');
    //CSVでダウンロード
    Route::get('generalSearchDownloadAsCSV/{job_id}', 'App\Http\Controllers\GeneralSearchController@generalSearchDownloadAsCSV');
    //汎用検索キャンセル
    Route::get('generalSearchCancel', 'App\Http\Controllers\GeneralSearchController@generalSearchCancel');
    //検索条件一覧取得
    Route::get('getGenralSearchSaveList', 'App\Http\Controllers\GeneralSearchController@getGenralSearchSaveList');
    //保存した条件指定取得
    Route::get('getGeneralSearchSave', 'App\Http\Controllers\GeneralSearchController@getGeneralSearchSave');
    // Delete General Search Condition
    Route::post('deleteGeneralSearchCondition', 'App\Http\Controllers\GeneralSearchController@deleteGeneralSearchCondition');
    //保存名チェック（重複しているかチェック）
    Route::get('checkGeneralSearchSaveName', 'App\Http\Controllers\GeneralSearchController@checkGeneralSearchSaveName');
    //検索条件保存（新規／更新）
    Route::post('saveGeneralSearchCondition', 'App\Http\Controllers\GeneralSearchController@saveGeneralSearchCondition');
    //システム管理
    Route::post('reset_password_all', '\App\Http\Controllers\ManagementController@reset_password_all');
    Route::post('reset_password_user', '\App\Http\Controllers\ManagementController@reset_password_user');
    Route::post('create_attendance_table', '\App\Http\Controllers\ManagementController@create_attendance_table');
    Route::post('create_attendance_table_by_code', '\App\Http\Controllers\ManagementController@create_attendance_table_by_code');
    Route::get('getJobState', '\App\Http\Controllers\ManagementController@getJobState');
    Route::post('cancelJob', '\App\Http\Controllers\ManagementController@cancelJob');
    Route::post('aggregate_attendance', '\App\Http\Controllers\ManagementController@aggregate_attendance');
    Route::post('aggregate_attendance_byid', '\App\Http\Controllers\ManagementController@aggregateAttendanceByID');
});


//検証用Routeはmiddleware1通す
Route::middleware(['checkDevMode'])->group(function () {
    //動作検証用
    Route::get('test_model', 'App\Http\Controllers\UnAuthController@test_model');
    //Sessionデータ確認用
    Route::get('test_session', 'App\Http\Controllers\ShachihataController@test_session');
    //ユーザーをm007_employeeからusersへコピーするときに使用
    Route::get('/copy_users','App\Http\Controllers\ShachihataController@copyUserInfo');
    //デフォルトパスへ強制リセット
    Route::get('/reset_password','App\Http\Controllers\ShachihataController@setDefaultPassword');
    //後藤さん検証用
    Route::get('/test_goto', function () {
        return view('test_goto');
    });
    
    Route::get('test_speed','App\Http\Controllers\ShachihataController@testSpeed');
});

//Admin画面
 Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function() {
     Route::get('home', '\App\Http\Controllers\Admin\ManagementController@index')->name('home');
     Route::get('login', '\App\Http\Controllers\Admin\Auth\LoginController@showLoginForm')->name('login');
     Route::post('login', '\App\Http\Controllers\Admin\Auth\LoginController@login');
     Route::post('logout', '\App\Http\Controllers\Admin\Auth\LoginController@logout')->name('logout');
     Route::post('reset_password_all', '\App\Http\Controllers\Admin\ManagementController@reset_password_all');
     Route::post('reset_password_user', '\App\Http\Controllers\Admin\ManagementController@reset_password_user');
     Route::post('create_attendance_table', '\App\Http\Controllers\Admin\ManagementController@create_attendance_table');
     Route::post('create_attendance_table_byeid', '\App\Http\Controllers\Admin\ManagementController@create_attendance_table_byeid');
     Route::get('getJobState', '\App\Http\Controllers\Admin\ManagementController@getJobState');
     Route::post('cancelJob', '\App\Http\Controllers\Admin\ManagementController@cancelJob');
     Route::post('aggregate_attendance', '\App\Http\Controllers\Admin\ManagementController@aggregate_attendance');
     Route::post('aggregate_attendance_byid', '\App\Http\Controllers\Admin\ManagementController@aggregateAttendanceByID');
     Route::get('getCompanyList', '\App\Http\Controllers\Admin\ManagementController@getCompanyList');
     Route::get('getCompanyInfo', '\App\Http\Controllers\Admin\ManagementController@getCompanyInfo');
     Route::post('updateCompany', '\App\Http\Controllers\Admin\ManagementController@updateCompany');
     Route::post('createCompany', '\App\Http\Controllers\Admin\ManagementController@createCompany');
});
