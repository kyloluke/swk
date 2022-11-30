<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT008UnemployedInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t008_unemployed_information', function (Blueprint $table) {
            $table->bigIncrements('unemployed_information_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('target_date')->default(0);
            $table->unsignedBigInteger('unemployed_no')->default(0);
            $table->unsignedBigInteger('unemployed_id')->default(0);
            $table->unsignedInteger('unemployed_time_start')->default(0);
            $table->unsignedInteger('unemployed_time_end')->default(0);
            $table->text('request_reason');
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['unemployed_information_id','employee_id','target_date','unemployed_no'],'unemployed_information_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t008_unemployed_information');
    }
}
