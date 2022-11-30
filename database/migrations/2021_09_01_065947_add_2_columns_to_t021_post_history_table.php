<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2ColumnsToT021PostHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t021_post_history', function (Blueprint $table) {
            $table->string('post_name',100)->nullable()->after('post_code');
            $table->string('post_short_name',100)->nullable()->after('post_name');
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
            $table->dropColumn('post_name');
            $table->dropColumn('post_short_name');
        });
    }
}
