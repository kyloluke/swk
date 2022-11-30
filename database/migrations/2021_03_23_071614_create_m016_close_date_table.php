<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM016CloseDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m016_close_date', function (Blueprint $table) {
            $table->bigIncrements('close_date_id');
            $table->unsignedInteger('close_date')->default(0);
            $table->string('close_date_name',100)->nullable();
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
        Schema::dropIfExists('m016_close_date');
    }
}
