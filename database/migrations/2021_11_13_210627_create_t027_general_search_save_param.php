<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateT027GeneralSearchSaveParam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t027_general_search_save_param', function (Blueprint $table) {
            $table->bigIncrements('general_search_save_param_id');
            $table->unsignedBigInteger('general_search_save_id');
            $table->unsignedinteger('kind')->default(0);
            $table->unsignedinteger('columns')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t027_general_search_save_param');
    }
}
