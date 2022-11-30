<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM014OverTimeClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('over_time_class_name');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_over_time_class_id')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m014_over_time_class', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_over_time_class_id');
        });
    }
}
