<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM037ViolationWarningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m037_violation_warning', function (Blueprint $table) {
            $table->bigIncrements('violation_warning_id');
            $table->string('violation_warning_name',100)->nullable();
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
        Schema::dropIfExists('m037_violation_warning');
    }
}
