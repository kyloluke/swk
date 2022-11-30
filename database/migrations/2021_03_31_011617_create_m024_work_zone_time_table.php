<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM024WorkZoneTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m024_work_zone_time', function (Blueprint $table) {
            $table->bigIncrements('work_zone_time_id');
            $table->string('work_zone_code',20);
            $table->unsignedInteger('time_type_clas')->default(0);
            $table->unsignedInteger('start_time')->default(0);
            $table->unsignedInteger('end_time')->default(0);
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
        Schema::dropIfExists('m024_work_zone_time');
    }
}
