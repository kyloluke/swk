<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM005DeptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m005_dept', function (Blueprint $table) {
            $table->unsignedinteger('is_delete')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m005_dept', function (Blueprint $table) {
            $table->integer('is_delete')->default(0)->change();
        });
    }
}
