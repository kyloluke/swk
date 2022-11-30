<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT006SetInputAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t006_set_input_agent', function (Blueprint $table) {
            $table->bigIncrements('set_input_agent_id');
            $table->unsignedBigInteger('input_agent_id')->default(0);    
            $table->unsignedBigInteger('input_delegator_id')->default(0);
            $table->unsignedInteger('valid_date_start')->default(0);
            $table->unsignedInteger('varid_date_end')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['set_input_agent_id','input_agent_id','input_delegator_id'],'set_input_agent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t006_set_input_agent');
    }
}
