<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM017RestTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m017_rest_time', function (Blueprint $table) {
            $table->bigIncrements('rest_time_id');
            $table->string('rest_time_name',100)->nullable();
            $table->unsignedinteger('work_time_from')->default(0);
            $table->unsignedinteger('work_time_to')->default(0);
            $table->unsignedinteger('rest_time')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('create_user',20)->default("0");
            $table->timestamp('created_at')->useCurrent();
            $table->string('update_user',20)->default("0");
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
        Schema::dropIfExists('m017_rest_time');
    }
}
