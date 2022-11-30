<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM011AuthorityPatternTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m011_authority_pattern', function (Blueprint $table) {
            $table->bigIncrements('authority_pattern_id');
            $table->unsignedInteger('proxy_input_menu')->default(0);
            $table->unsignedInteger('proxy_attendance_input')->default(0);
            $table->unsignedInteger('proxy_input_target_setting')->default(0);
            $table->unsignedInteger('attendance_admin_menu')->default(0);
            $table->unsignedInteger('attendance_admin_inquiry_approval')->default(0);
            $table->unsignedInteger('attendance_admin_work_vacation_management')->default(0);
            $table->unsignedInteger('attendance_admin_daily_report')->default(0);
            $table->unsignedInteger('attendance_admin_approval_target_setting')->default(0);
            $table->unsignedInteger('office_menu')->default(0);
            $table->unsignedInteger('office_attendance_input')->default(0);
            $table->unsignedInteger('office_work_vacation_management')->default(0);
            $table->unsignedInteger('office_closing')->default(0);
            $table->unsignedInteger('office_vacation_management')->default(0);
            $table->unsignedInteger('office_general_search')->default(0);
            $table->unsignedInteger('office_master_management')->default(0);
            $table->unsignedInteger('general_affairs_menu')->default(0);
            $table->unsignedInteger('general_affairs_attendance_input')->default(0);
            $table->unsignedInteger('general_affairs_work_vacation_management')->default(0);
            $table->unsignedInteger('general_affairs_closing')->default(0);
            $table->unsignedInteger('general_affairs_general_search')->default(0);
            $table->unsignedInteger('general_affairs_vacation_management')->default(0);
            $table->unsignedInteger('general_affairs_data_input_output')->default(0);
            $table->unsignedInteger('general_affairs_master_management')->default(0);
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
        Schema::dropIfExists('m011_authority_pattern');
    }
}
