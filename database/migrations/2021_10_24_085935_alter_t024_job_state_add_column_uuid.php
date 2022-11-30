<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT024JobStateAddColumnUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->default(0)->after('admin_id');
            $table->uuid('job_id')->after('job_state_id');
            $table->unsignedBigInteger('admin_id')->default(0)->change();
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
        Schema::table('t024_job_state', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->dropColumn('job_id');
            $table->unsignedBigInteger('admin_id')->change();
        });
    }
}
