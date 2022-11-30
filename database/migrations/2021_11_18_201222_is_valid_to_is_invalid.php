<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IsValidToIsInvalid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m001_information_type', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m029_theme', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->renameColumn('is_valid', 'is_invalid');
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
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m012_work_style', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m013_employment_style', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m016_close_date', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m021_calendar', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m029_theme', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m030_work_achievement', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m032_grant_paid_leave_conditions_pattern', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m033_grant_paid_leave_pattern', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
        Schema::table('m043_holiday', function (Blueprint $table) {
            $table->renameColumn('is_invalid', 'is_valid');
        });
    }
}
