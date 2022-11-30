<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT001WebPunchClockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t001_web_punch_clock', function (Blueprint $table) {
            $table->bigIncrements('web_punch_clock_id');
            $table->unsignedBigInteger('employee_id')->default(0);
            $table->integer('punch_clock_date')->default(0);
            $table->unsignedBigInteger('clocking_in_out_id')->default(0);
            $table->integer('punch_clock_time')->nullable();
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t001_web_punch_clock');
    }
}
