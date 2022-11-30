<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT022LogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t022_log', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->unsignedBigInteger('window_id')->default(0);    
            $table->unsignedBigInteger('operation_id')->default(0);    
            $table->unsignedInteger('log_class')->default(0);
            $table->text('log_text')->nullable();
            $table->unsignedBigInteger('login_employee_id')->default(0);    
            $table->unsignedBigInteger('operation_target_id')->default(0);    
            $table->timestamp('log_datetime')->useCurrent();
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique('log_id','log_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t022_log');
    }
}
