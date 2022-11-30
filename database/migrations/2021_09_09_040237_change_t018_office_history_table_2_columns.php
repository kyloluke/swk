<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeT018OfficeHistoryTable2Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('t018_office_history')->where('office_name', null)->update(['office_name' => ""]);
        DB::table('t018_office_history')->where('office_short_name', null)->update(['office_short_name' => ""]);
        Schema::table('t018_office_history', function (Blueprint $table) {
            $table->string('office_name',100)->nullable(false)->change();
            $table->string('office_short_name',100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t018_office_history', function (Blueprint $table) {
            $table->string('office_name',100)->nullable()->change();
            $table->string('office_short_name',100)->nullable()->change();
        });
    }
}
