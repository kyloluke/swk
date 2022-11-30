<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM020HolidaySystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m020_holiday_system', function (Blueprint $table) {
            $table->bigIncrements('holiday_system_id');
            $table->string('holiday_system_name',100)->nullable();
            $table->unsignedinteger('deformation_holiday_class')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m020_holiday_system');
    }
}
