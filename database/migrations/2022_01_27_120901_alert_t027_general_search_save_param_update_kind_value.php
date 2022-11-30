<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlertT027GeneralSearchSaveParamUpdateKindValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t027_general_search_save_param', function($table)
        {
            $table->unsignedinteger('kind')->nullable()->change();
        });
        
        DB::table('t027_general_search_save_param')
            ->where('kind', 8)
            ->update(['kind' => 9]);
        DB::table('t027_general_search_save_param')
            ->where('kind', 7)
            ->update(['kind' => 8]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::table('t027_general_search_save_param')
            ->where('kind', 7)
            ->orWhere('kind', '>=', 10)->delete();

        DB::table('t027_general_search_save_param')
            ->where('kind', 8)
            ->update(['kind' => 7]);
        DB::table('t027_general_search_save_param')
            ->where('kind', 9)
            ->update(['kind' => 8]);
        
        Schema::table('t027_general_search_save_param', function($table)
        {
            $table->unsignedInteger('kind')->default(0)->change();
        });
    }
}
