<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM015DeductionReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('deduction_reason');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_deduction_reason_id')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m015_deduction_reason', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_deduction_reason_id');
        });
    }
}
