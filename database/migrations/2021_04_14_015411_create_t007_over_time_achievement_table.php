<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT007OverTimeAchievementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t007_over_time_achievement', function (Blueprint $table) {
            $table->bigIncrements('over_time_achievement_id');
            $table->unsignedBigInteger('employee_id')->default(0);    
            $table->unsignedInteger('target_date')->default(0);
            $table->unsignedBigInteger('over_time_no')->default(0);
            $table->unsignedInteger('over_time_class')->default(0);
            $table->unsignedInteger('over_time_start')->default(0);
            $table->unsignedInteger('over_time_end')->default(0);
            $table->unsignedInteger('over_time_rest_time')->default(0);
            $table->unsignedInteger('over_time_midnight_rest_time')->default(0);
            $table->unsignedInteger('deduction_time')->default(0);
            $table->unsignedBigInteger('deduction_reason_id')->default(0);
            $table->text('deduction_reason');
            $table->text('over_time_reason');
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['over_time_achievement_id','employee_id','target_date','over_time_no'],'over_time_achievement_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t007_over_time_achievement');
    }
}
