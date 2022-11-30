<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM027WorkHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->unsignedBigInteger('work_holiday_class')->default(0)->after('work_holiday_short_name');
            $table->unsignedBigInteger('company_id')->after('work_holiday_class');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_work_holiday_id')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m027_work_holiday', function (Blueprint $table) {
            $table->dropColumn('work_holiday_class');
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_work_holiday_id');
        });
    }
}
