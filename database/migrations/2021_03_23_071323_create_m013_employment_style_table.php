<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM013EmploymentStyleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m013_employment_style', function (Blueprint $table) {
            $table->bigIncrements('employment_style_id');
            $table->string('employment_style_name',100)->nullable();
            $table->string('employment_style_short_name',100)->nullable();
            $table->unsignedinteger('hourly_wage_target')->default(0);
            $table->unsignedinteger('manegement_free_time')->default(0);
            $table->unsignedinteger('absence_deduction_target')->default(0);
            $table->unsignedinteger('childcare_deduction_target')->default(0);
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
        Schema::dropIfExists('m013_employment_style');
    }
}
