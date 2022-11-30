<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT004SubstituteInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->index(['employee_id', 'substitute_holiday_date', 'is_delete'], 't004_esi_index');
            $table->index(['substitute_holiday_date', 'is_delete'], 't004_si_index');
            $table->index(['attendance_information_id', 'is_delete'], 't004_ai_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t004_substitute_information', function (Blueprint $table) {
            $table->dropIndex('t004_esi_index');
            $table->dropIndex('t004_si_index');
            $table->dropIndex('t004_ai_index');
        });
    }
}
