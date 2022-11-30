<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT001WebPunchClockAddTranceferFlag extends Migration
{
        /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->unsignedInteger('transfer_class')->default(0)->after('punch_clock_time');
        });
    }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        //
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->dropColumn('transfer_class');
        });
    }
}
