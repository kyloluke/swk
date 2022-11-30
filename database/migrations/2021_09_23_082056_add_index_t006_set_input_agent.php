<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT006SetInputAgent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t006_set_input_agent', function (Blueprint $table) {
            $table->index(['input_agent_id', 'valid_date_start', 'valid_date_end', 'is_delete'], 't006_ivvi_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t006_set_input_agent', function (Blueprint $table) {
            $table->dropIndex('t006_ivvi_index');
        });
    }
}
