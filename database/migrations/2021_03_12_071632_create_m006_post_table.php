<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM006PostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m006_post', function (Blueprint $table) {
            $table->bigIncrements('post_id');
            $table->string('post_code',20);
            $table->string('post_name',100)->nullable();
            $table->string('post_short_name',100)->nullable();
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->integer('is_delete')->default(0);
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
        Schema::dropIfExists('m006_post');
    }
}
