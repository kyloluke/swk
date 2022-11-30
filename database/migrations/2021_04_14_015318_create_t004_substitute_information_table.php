<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT004SubstituteInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t004_substitute_information', function (Blueprint $table) {
            $table->bigIncrements('substitute_information_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('holiday_substitute_date')->default(0);
            $table->unsignedInteger('substitute_holiday_date')->default(0);
            $table->unsignedInteger('acquired_substitue_holiday_date')->default(0);
            $table->text('substitue_reason');
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['substitute_information_id','employee_id','holiday_substitute_date','substitute_holiday_date'],'substitute_information_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t004_substitute_information');
    }
}
