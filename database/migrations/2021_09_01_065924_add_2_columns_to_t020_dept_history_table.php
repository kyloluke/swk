<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2ColumnsToT020DeptHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->string('dept_name',100)->nullable()->after('dept_code');
            $table->string('dept_short_name',100)->nullable()->after('dept_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->dropColumn('dept_name');
            $table->dropColumn('dept_short_name');
        });
    }
}
