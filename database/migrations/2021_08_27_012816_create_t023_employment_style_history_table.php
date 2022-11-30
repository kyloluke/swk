<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT023EmploymentStyleHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t023_employment_style_history', function (Blueprint $table) {
            $table->bigIncrements('employment_style_history_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedBigInteger('employment_style_id')->default(0);
            $table->string('employment_style_name',100)->nullable();
            $table->string('employment_style_short_name',100)->nullable();
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['employment_style_history_id','employee_id','employment_style_id'],'employment_style_history_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t023_employment_style_history');
    }
}
