<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM003CompnayAddColumnBeginningMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m003_company', function (Blueprint $table) {
            $table->unsignedInteger('beginning_month')->default(1)->after('company_short_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m003_company', function (Blueprint $table) {
            $table->dropColumn('beginning_month');
        });
    }
}
