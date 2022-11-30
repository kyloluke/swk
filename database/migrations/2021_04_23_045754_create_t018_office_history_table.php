<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT018OfficeHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t018_office_history', function (Blueprint $table) {
            $table->bigIncrements('office_history_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedBigInteger('office_id')->default(0);
            $table->string('office_code',20)->nullable();
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['office_history_id','employee_id','office_id'],'office_history_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t018_office_history');
    }
}
