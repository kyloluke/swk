<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03436agreementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->dropColumn('36agreement_start_date');
            $table->dropColumn('36agreement_apply_class');
            $table->dropColumn('36agreement_apply_class_name');
            $table->unsignedBigInteger('36agreement_apply_id')->default(0);
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('valid_date_end')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->dropColumn('36agreement_apply_id');
            $table->dropColumn('valid_date_start');
            $table->dropColumn('valid_date_end');
            $table->unsignedinteger('36agreement_start_date')->default(0);
            $table->unsignedinteger('36agreement_apply_class')->default(0);
            $table->string('36agreement_apply_class_name',100)->nullable();
        });
    }
}
