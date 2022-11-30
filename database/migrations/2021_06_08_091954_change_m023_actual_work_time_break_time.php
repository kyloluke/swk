<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeM023ActualWorkTimeBreakTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m023_work_zone', function (Blueprint $table) {
            $table->unsignedInteger('actual_work_time')->default(0)->change();
            $table->unsignedInteger('break_time')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //既に値が入っている場合はエラーとなるため、downメソッドを除外
        // $table->string('actual_work_time')->change();
        // $table->string('break_time')->change();
    }
}
