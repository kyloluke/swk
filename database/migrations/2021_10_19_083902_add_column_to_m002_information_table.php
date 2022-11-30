<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToM002InformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m002_information', function (Blueprint $table) {
            $table->unsignedBigInteger('display_company_id')->default(0)->after('information_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m002_information', function (Blueprint $table) {
            $table->dropColumn('display_company_id');
        });
    }
}
