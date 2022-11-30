<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM03536agreementAggregateClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->bigIncrements('36agreement_aggregate_class_id');
            $table->unsignedBigInteger('36agreement_id')->default(0);
            $table->unsignedinteger('36agreement_aggregate_class')->default(0);
            $table->string('36agreement_aggregate_class_name',100)->nullable();
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
        Schema::dropIfExists('m035_36agreement_aggregate_class');
    }
}
