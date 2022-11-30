<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertUpdatedAtAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m001_information_type', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m001_information_type', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m002_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m002_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m003_company', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m003_company', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m004_office', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m004_office', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m005_dept', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m005_dept', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m006_post', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m006_post', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m008_system_administrator', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m008_system_administrator', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m009_display_color', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m009_display_color', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m010_message', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m010_message', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m011_authority_pattern', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m011_authority_pattern', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m017_rest_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m017_rest_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m018_approval_state', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m018_approval_state', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m019_close_state', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m019_close_state', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m025_clocking_in_out', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m025_clocking_in_out', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m026_week', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m026_week', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m029_theme', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m029_theme', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m037_violation_warning', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m037_violation_warning', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m038_violation_warning_type', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m038_violation_warning_type', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m041_window_name', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m041_window_name', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m042_operation_name', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m042_operation_name', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m044_holiday_summary', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m044_holiday_summary', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('m045_company_access_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m045_company_access_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t006_set_input_agent', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t006_set_input_agent', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t010_acquired_holiday', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t010_acquired_holiday', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t011_holiday_worker_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t011_holiday_worker_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t012_overwork_employee_monthly', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t012_overwork_employee_monthly', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t013_overwork_employee_year', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t013_overwork_employee_year', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t014_office_closing_status', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t014_office_closing_status', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t015_company_closing_status', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t015_company_closing_status', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t016_paid_leave_reference_date', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t016_paid_leave_reference_date', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t018_office_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t018_office_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t022_log', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t022_log', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t023_employment_style_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t023_employment_style_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
        Schema::table('t025_information_read', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t025_information_read', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('updated_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m001_information_type', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m001_information_type', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m002_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m002_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m003_company', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m003_company', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m004_office', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m004_office', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m005_dept', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m005_dept', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m006_post', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m006_post', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m008_system_administrator', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m008_system_administrator', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m009_display_color', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m009_display_color', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m010_message', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m010_message', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m011_authority_pattern', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m011_authority_pattern', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m017_rest_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m017_rest_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m018_approval_state', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m018_approval_state', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m019_close_state', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m019_close_state', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m022_calendar_setting', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m024_work_zone_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m025_clocking_in_out', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m025_clocking_in_out', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m026_week', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m026_week', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m029_theme', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m029_theme', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m037_violation_warning', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m037_violation_warning', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m038_violation_warning_type', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m038_violation_warning_type', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m041_window_name', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m041_window_name', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m042_operation_name', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m042_operation_name', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m044_holiday_summary', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m044_holiday_summary', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('m045_company_access_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('m045_company_access_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t002_attendance_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t006_set_input_agent', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t006_set_input_agent', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t007_over_time_achievement', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t009_holiday_management', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t010_acquired_holiday', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t010_acquired_holiday', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t011_holiday_worker_information', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t011_holiday_worker_information', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t012_overwork_employee_monthly', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t012_overwork_employee_monthly', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t013_overwork_employee_year', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t013_overwork_employee_year', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t014_office_closing_status', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t014_office_closing_status', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t015_company_closing_status', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t015_company_closing_status', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t016_paid_leave_reference_date', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t016_paid_leave_reference_date', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t018_office_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t018_office_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t019_work_closing_belonging_office_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t022_log', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t022_log', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t023_employment_style_history', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t023_employment_style_history', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
        Schema::table('t025_information_read', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('t025_information_read', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->after('updated_user');
        });
    }
}
