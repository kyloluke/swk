<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT017DailyReportAddTheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->unsignedInteger('theme_id')->default(0)->after('work_time_end');
        });
        DB::table('t017_daily_report')->whereNotIn('work_item_name', [''])->update(['theme_id' => 1]);
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->dropColumn('work_item_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->text('work_item_name')->after('work_time_end');
        });
        //テーマが1しかないこと前提
        DB::table('t017_daily_report')->whereNotIn('theme_id', [0])->update(['work_item_name' => '定常業務']);
        Schema::table('t017_daily_report', function (Blueprint $table) {
            $table->dropColumn('theme_id');
        });
    }
}
