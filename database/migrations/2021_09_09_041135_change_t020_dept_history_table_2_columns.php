<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeT020DeptHistoryTable2Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('t020_dept_history')->where('dept_name', null)->update(['dept_name' => ""]);
        DB::table('t020_dept_history')->where('dept_short_name', null)->update(['dept_short_name' => ""]);
        Schema::table('t020_dept_history', function (Blueprint $table) {
            $table->string('dept_name',100)->nullable(false)->change();
            $table->string('dept_short_name',100)->nullable(false)->change();
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
            $table->string('dept_name',100)->nullable()->change();
            $table->string('dept_short_name',100)->nullable()->change();
        });
    }
}
