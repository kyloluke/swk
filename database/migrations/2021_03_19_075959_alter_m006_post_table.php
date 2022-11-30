<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM006PostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m006_post', function (Blueprint $table) {
            $table->unsignedinteger('is_delete')->default(0)->change();
            $table->unsignedBigInteger('company_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m006_post', function (Blueprint $table) {
            $table->integer('is_delete')->default(0)->change();
            $table->dropColumn('company_id');
        });
    }
}
