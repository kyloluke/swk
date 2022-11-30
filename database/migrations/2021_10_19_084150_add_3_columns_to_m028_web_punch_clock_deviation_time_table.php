<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM028WebPunchClockDeviationTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('allow_after_end_time');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_web_punch_clock_deviation_time_id')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m028_web_punch_clock_deviation_time', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_web_punch_clock_deviation_time_id');
        });
    }
}
