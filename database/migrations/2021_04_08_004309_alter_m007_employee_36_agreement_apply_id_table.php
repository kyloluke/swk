<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007Employee36AgreementApplyIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->dropColumn('36_agreement_id');
            $table->unsignedBigInteger('36_agreement_apply_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->dropColumn('36_agreement_apply_id');
            $table->unsignedBigInteger('36_agreement_id')->nullable();
        });
    }
}
