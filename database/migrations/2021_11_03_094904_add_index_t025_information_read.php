<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT025InformationRead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t025_information_read', function (Blueprint $table) {
            $table->index(['employee_id', 'information_id', 'is_delete'], 't025_ei_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t025_information_read', function (Blueprint $table) {
            $table->dropIndex('t025_ei_index');
        });
    }
}
