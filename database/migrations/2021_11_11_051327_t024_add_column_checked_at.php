<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class T024AddColumnCheckedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->timestamp('checked_at')->after('progress')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->dropColumn('checked_at');
        });
    }
}
