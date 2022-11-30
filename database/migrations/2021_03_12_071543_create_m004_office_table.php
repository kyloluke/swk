<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM004OfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m004_office', function (Blueprint $table) {
            $table->bigIncrements('office_id');
            $table->unsignedInteger('company_id')->default(0);
            $table->string('office_code',20);
            $table->string('office_name',100)->nullable();
            $table->string('office_short_name',100)->nullable();
            $table->integer('paid_take_unit_class')->default(0);
            $table->integer('over_time_calc_class')->default(0);
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
        Schema::dropIfExists('m004_office');
    }
}
