<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM039PreventionOverworkCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m039_prevention_overwork_check', function (Blueprint $table) {
            $table->bigIncrements('prevention_overwork_check_id');
            $table->unsignedBigInteger('violation_warning_type_id')->default(0);
            $table->unsignedBigInteger('violation_warning_id')->default(0);
            $table->unsignedInteger('time_or_count')->default(0);
            $table->text('message')->nullable();
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
            $table->unsignedInteger('valid_class')->default(0);
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
        Schema::dropIfExists('m039_prevention_overwork_check');
    }
}
