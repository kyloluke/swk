<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnOriginIdAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m001_information_type', function (Blueprint $table) {
            $table->renameColumn('origin_information_type_id', 'origin_id');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->renameColumn('origin_work_style_id', 'origin_id');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->renameColumn('origin_employment_style_id', 'origin_id');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->renameColumn('origin_over_time_class_id', 'origin_id');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->renameColumn('origin_deduction_reason_id', 'origin_id');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->renameColumn('origin_close_date_id', 'origin_id');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->renameColumn('origin_work_holiday_id', 'origin_id');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->renameColumn('origin_web_punch_clock_deviation_time_id', 'origin_id');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->renameColumn('origin_grant_paid_leave_conditions_pattern_id', 'origin_id');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->renameColumn('origin_grant_paid_leave_pattern_id', 'origin_id');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->renameColumn('origin_thirtysix_agreement_aggregate_class_id', 'origin_id');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->renameColumn('origin_prevention_overwork_check_id', 'origin_id');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->renameColumn('origin_holiday_id', 'origin_id');
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
            $table->renameColumn('origin_id', 'origin_information_type_id');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_work_style_id');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_employment_style_id');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_over_time_class_id');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_deduction_reason_id');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_close_date_id');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_work_holiday_id');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_web_punch_clock_deviation_time_id');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_grant_paid_leave_conditions_pattern_id');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_grant_paid_leave_pattern_id');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_thirtysix_agreement_aggregate_class_id');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_prevention_overwork_check_id');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->renameColumn('origin_id', 'origin_holiday_id');
        });
    }
}
