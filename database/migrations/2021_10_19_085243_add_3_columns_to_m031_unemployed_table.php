<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM031UnemployedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('manual_grant_enabled_class');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_unemployed_id')->nullable()->after('is_valid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m031_unemployed', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_unemployed_id');
        });
    }
}
