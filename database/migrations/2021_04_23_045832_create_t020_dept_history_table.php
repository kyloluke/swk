<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT020DeptHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t020_dept_history', function (Blueprint $table) {
            $table->bigIncrements('dept_history_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedBigInteger('dept_id')->default(0);
            $table->string('dept_code',20)->nullable();
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['dept_history_id','employee_id','dept_id'],'dept_history_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t020_dept_history');
    }
}
