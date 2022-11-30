<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT015CompanyClosingStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t015_company_closing_status', function (Blueprint $table) {
            $table->bigIncrements('company_closing_status_id');
            $table->unsignedBigInteger('company_id')->default(0);    
            $table->unsignedInteger('company_closing_year_month')->default(0);
            $table->unsignedBigInteger('close_date_id')->default(0);
            $table->unsignedInteger('closing_status_class')->default(0);
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("SYSTEM");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("SYSTEM");
            $table->timestamp('updated_at')->useCurrent();
            $table->unique(['company_closing_status_id','company_id','company_closing_year_month'],'company_closing_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t015_company_closing_status');
    }
}
