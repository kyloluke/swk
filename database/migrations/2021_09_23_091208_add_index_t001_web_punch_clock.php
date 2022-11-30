<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT001WebPunchClock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->index(['employee_id', 'punch_clock_date', 'is_delete'], 't001_epi_index');
            $table->index(['transfer_class', 'is_delete'], 't001_ti_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t001_web_punch_clock', function (Blueprint $table) {
            $table->dropIndex('t001_epi_index');
            $table->dropIndex('t001_ti_index');
        });
    }
}
