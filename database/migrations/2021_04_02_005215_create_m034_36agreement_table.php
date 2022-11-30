<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM03436agreementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m034_36agreement', function (Blueprint $table) {
            $table->bigIncrements('36agreement_id');
            $table->unsignedinteger('36agreement_start_date')->default(0);
            $table->unsignedinteger('36agreement_apply_class')->default(0);
            $table->string('36agreement_apply_class_name',100)->nullable();
            $table->unsignedinteger('36agreement_special_provisions_max_count')->default(0);
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
        Schema::dropIfExists('m034_36agreement');
    }
}
