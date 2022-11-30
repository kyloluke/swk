<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeT023EmploymentStyleHistoryTable2Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('t023_employment_style_history')->where('employment_style_short_name', null)->update(['employment_style_short_name' => ""]);
        DB::table('t023_employment_style_history')->where('employment_style_name', null)->update(['employment_style_name' => ""]);
        Schema::table('t023_employment_style_history', function (Blueprint $table) {
            $table->string('employment_style_name',100)->nullable(false)->change();
            $table->string('employment_style_short_name',100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t023_employment_style_history', function (Blueprint $table) {
            $table->string('employment_style_name',100)->nullable()->change();
            $table->string('employment_style_short_name',100)->nullable()->change();
        });
    }
}
