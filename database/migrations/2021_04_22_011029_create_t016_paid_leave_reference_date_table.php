<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT016PaidLeaveReferenceDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t016_paid_leave_reference_date', function (Blueprint $table) {
            $table->bigIncrements('paid_leave_reference_date_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('paid_leave_reference_date')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['paid_leave_reference_date_id','employee_id','paid_leave_reference_date'],'paid_leave_reference_date_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t016_paid_leave_reference_date');
    }
}
