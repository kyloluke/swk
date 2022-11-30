<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT008UnemployedInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->index(['employee_id', 'target_date', 'is_delete'], 't008_eti_index');
            $table->index(['attendance_information_id', 'is_delete'], 't008_ai_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t008_unemployed_information', function (Blueprint $table) {
            $table->dropIndex('t008_eti_index');
            $table->dropIndex('t008_ai_index');
        });
    }
}
