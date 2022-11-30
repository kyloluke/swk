<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeT021PostHistoryTable2Columns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('t021_post_history')->where('post_name', null)->update(['post_name' => ""]);
        DB::table('t021_post_history')->where('post_short_name', null)->update(['post_short_name' => ""]);
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->string('post_name',100)->nullable(false)->change();
            $table->string('post_short_name',100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->string('post_name',100)->nullable()->change();
            $table->string('post_short_name',100)->nullable()->change();
        });
    }
}
