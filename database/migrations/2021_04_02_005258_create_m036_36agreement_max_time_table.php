<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM03636agreementMaxTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m036_36agreement_max_time', function (Blueprint $table) {
            $table->bigIncrements('36agreement_max_time_id');
            $table->unsignedBigInteger('36agreement_aggregate_class_id')->default(0);
            $table->integer('36agreement_aggregate_unit')->default(0);
            $table->integer('max_time')->default(0);
            $table->integer('holiday_work_aggregate_class')->default(0);
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
        Schema::dropIfExists('m036_36agreement_max_time');
    }
}
