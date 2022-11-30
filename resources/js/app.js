/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');

 window.Vue = require('vue').default;
 
 /**
  * The following block of code may be used to automatically register your
  * Vue components. It will recursively scan this directory for the Vue
  * components and automatically register them with their "basename".
  *
  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
  */
 
 // const files = require.context('./', true, /\.vue$/i)
 // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
 
 
 // Vue.component('example-component', require('./components/ExampleComponent.vue').default);
 Vue.component('header_button_area_auth', require('./../views/layouts/header_button_area_auth.vue').default);
 Vue.component('w002menu', require('./../views/layouts/w002_menu.vue').default);
 Vue.component('workingStatus_board', require('./../views/components/workingStatus_board.vue').default);
 Vue.component('aggrigateAttendancelist_board', require('./../views/components/aggrigateAttendanceList_board.vue').default);
 Vue.component('w004_top', require('./../views/w004_top/w004_top.vue').default);
 Vue.component('w005_themselves', require('./../views/w005_themselves/w005_themselves.vue').default);
 Vue.component('w006_substitute', require('./../views/w006_substitute/w006_substitute.vue').default);
 Vue.component('w007_attendance_manager', require('./../views/w007_attendance_manager/w007_attendance_manager.vue').default);
 Vue.component('w009_office', require('./../views/w009_office/w009_office.vue').default);
 Vue.component('w010_company', require('./../views/w010_company/w010_company.vue').default);
 Vue.component('w011_input_attendance', require('./../views/w005_themselves/w011_input_attendance.vue').default);
 Vue.component('w012_labor_situation', require('./../views/w005_themselves/w012_labor_situation.vue').default);
 Vue.component('w013_daily_report', require('./../views/w005_themselves/w013_daily_report.vue').default);
 Vue.component('w014_input_attendance', require('./../views/w006_substitute/w014_input_attendance.vue').default);
 Vue.component('w015_setting_target', require('./../views/w006_substitute/w015_setting_target.vue').default);
 Vue.component('w016_approval', require('./../views/w007_attendance_manager/w016_approval.vue').default);
 Vue.component('w017_labor_situation', require('./../views/w007_attendance_manager/w017_labor_situation.vue').default);
 Vue.component('w018_daily_report', require('./../views/w007_attendance_manager/w018_daily_report.vue').default);
 Vue.component('w019_setting_target', require('./../views/w007_attendance_manager/w019_setting_target.vue').default);
 Vue.component('w020_input_attendance', require('./../views/w009_office/w020_input_attendance.vue').default);
 Vue.component('w021_labor_situation', require('./../views/w009_office/w021_labor_situation.vue').default);
 Vue.component('w022_close_attendance', require('./../views/w009_office/w022_close_attendance.vue').default);
 Vue.component('w023_general_search', require('./../views/w009_office/w023_general_search.vue').default);
 Vue.component('w024_master_manage', require('./../views/w009_office/w024_master_manage.vue').default);
 Vue.component('w025_input_attendance', require('./../views/w010_company/w025_input_attendance.vue').default);
 Vue.component('w026_labor_situation', require('./../views/w010_company/w026_labor_situation.vue').default);
 Vue.component('w027_daily_report', require('./../views/w010_company/w027_daily_report.vue').default);
 Vue.component('w028_close_attendance', require('./../views/w010_company/w028_close_attendance.vue').default);
 Vue.component('w029_general_search', require('./../views/w010_company/w029_general_search.vue').default);
 Vue.component('w030_absent_manage', require('./../views/w010_company/w030_absent_manage.vue').default);
 Vue.component('w031_data_io', require('./../views/w010_company/w031_data_io.vue').default);
 Vue.component('w032_master_manage', require('./../views/w010_company/w032_master_manage.vue').default);
 Vue.component('w033_system_manage', require('./../views/w010_company/w033_system_manage.vue').default);
 Vue.component('laborSituation_board', require('./../views/components/laborSituation_board.vue').default);
 Vue.component('c003_01_web_punch', require('./../views/components/C003_01_web_punch.vue').default);
 Vue.component('c003-01_record_time_clock', require('./../views/components/C003-01_record_time_clock.vue').default);
 Vue.component('card', require('./../views/components/card.vue').default);
 Vue.component('inputTypeTime', require('./../views/components/inputTypeTime.vue').default);
 Vue.component('inputTypeTimeModel', require('./../views/components/inputTypeTimeModel.vue').default);
 Vue.component('card_1line', require('./../views/components/card_1line.vue').default);
 Vue.component('card_top', require('./../views/components/card_top.vue').default);
 Vue.component('c004-01-01_information_board', require('./../views/components/C004-01-01_information_board.vue').default);
 Vue.component('c004-01-02_information_board', require('./../views/components/C004-01-02_information_board.vue').default);
 Vue.component('c012_01_03_board', require('./../views/components/C012_01_03_board.vue').default);
 Vue.component('c012_01_04_board', require('./../views/components/C012_01_04_board.vue').default);
 Vue.component('c012_01_05_board', require('./../views/components/C012_01_05_board.vue').default);
 Vue.component('c012_01_06_board', require('./../views/components/C012_01_06_board.vue').default);
 Vue.component('employee_info_board', require('./../views/components/employee_info_board.vue').default);
 Vue.component('c032_01_02_work_zone_board', require('./../views/components/c032_01_02_work_zone_board.vue').default);
 Vue.component('dailyreportlist_board', require('./../views/components/dailyReportList_board.vue').default);
 Vue.component('laborManagementSearch_board', require('./../views/components/laborManagementSearch_board.vue').default);
 Vue.component('laborManagementList_board', require('./../views/components/laborManagementList_board.vue').default);
 Vue.component('inputAttendance_board', require('./../views/components/inputAttendance_board.vue').default);
 Vue.component('informationattendancelist_board', require('./../views/components/informationAttendanceList_board.vue').default);
 Vue.component('inputAgentList_board', require('./../views/components/inputAgentList_board.vue').default);
 Vue.component('absent_manage_board', require('./../views/components/absent_manage_board.vue').default);
 Vue.component('settingTargetBoards', require('./../views/components/settingTargetBoards.vue').default);
 Vue.component('targetPersonStatusList', require('./../views/components/targetPersonStatusList.vue').default);
 Vue.component('information_info_board', require('./../views/components/information_info_board.vue').default);
 Vue.component('calendar_info_board', require('./../views/components/calendar_info_board.vue').default);
 Vue.component('multi_login', require('./../views/components/multi_login.vue').default);
 Vue.component('masterSettingOtherMaster', require('./../views/components/masterSetting/masterSettingOtherMaster.vue').default);
 Vue.component('masterSettingOrganizationMaster', require('./../views/components/masterSetting/masterSettingOrganizationMaster.vue').default);
 Vue.component('generalSearch_board', require('./../views/components/generalSearch_board.vue').default);
 //なぜかここで定義しないと開かない・・・？
 Vue.component('m002_information', require('./../views/modal/m102_information.vue').default);
 //管理画面
 Vue.component('management_menu', require('./../views/layouts/admin/management_menu.vue').default);
 Vue.component('management_001_company', require('./../views/layouts/admin/management_001_company.vue').default);
 Vue.component('management_002_master_data', require('./../views/layouts/admin/management_002_master_data.vue').default);
 Vue.component('management_edit_company', require('./../views/layouts/admin/modal/management_edit_company.vue').default);
 //modal内なのでグローバルの必要はないはずだけれど・・・
 // Vue.component('c105-01-header', require('./../views/components/inputAttendanceDetailContents/attendanceDetailHeader.vue').default);
 
 /**
  * Next, we will create a fresh Vue application instance and attach it to
  * the page. Then, you may begin adding components to this application
  * or customize the JavaScript scaffolding to fit your unique needs.
  */
 
 require('./modal_main');
 require('./AppLibs/commonFunctions');
 require('./AppLibs/dataFunctions');
 
 const app = new Vue({
     el: '#app',
 });