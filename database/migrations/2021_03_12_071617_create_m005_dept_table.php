<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM005DeptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m005_dept', function (Blueprint $table) {
            $table->bigIncrements('dept_id');
            $table->unsignedBigInteger('office_id')->default(0);
            $table->string('dept_code',20);
            $table->unsignedBigInteger('belonging_dept_id')->default(0)->nullable();
            $table->string('dept_name',100)->nullable();
            $table->string('dept_short_name',100)->nullable();
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
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
        Schema::dropIfExists('m005_dept');
    }
}
