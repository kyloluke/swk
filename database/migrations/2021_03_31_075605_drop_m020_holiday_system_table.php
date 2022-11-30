<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropM020HolidaySystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('m020_holiday_system');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('m020_holiday_system', function (Blueprint $table) {
            $table->bigIncrements('holiday_system_id');
            $table->string('holiday_system_name',100)->nullable();
            $table->unsignedinteger('deformation_holiday_class')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("0");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("0");
            $table->timestamp('updated_at')->useCurrent();
        });
    }
}
