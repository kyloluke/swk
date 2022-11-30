<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM031UnemployedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m031_unemployed', function (Blueprint $table) {
            $table->bigIncrements('unemployed_id');
            $table->string('unemployed_code',20);
            $table->string('unemployed_name',100)->nullable();
            $table->string('unemployed_short_name',100)->nullable();
            $table->unsignedinteger('unemployed_take_unit_class')->default(0);
            $table->unsignedinteger('holiday_management_class')->default(0);
            $table->unsignedinteger('max_days')->default(0);
            $table->unsignedinteger('paid_leave_target_class')->default(0);
            $table->unsignedinteger('work_day_target_class')->default(0);
            $table->unsignedinteger('deduction_target_class')->default(0);
            $table->unsignedinteger('bonus_absenteeism_deduction_class')->default(0);
            $table->unsignedinteger('manual_grant_enabled_class')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
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
        Schema::dropIfExists('m031_unemployed');
    }
}
