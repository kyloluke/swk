<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT005SetApprovalTargetValidDateEndTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->renameColumn('varid_date_end', 'valid_date_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->renameColumn('valid_date_end', 'varid_date_end');
        });
    }
}
