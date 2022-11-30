<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexT005SetApprovalTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t005_set_approval_target', function (Blueprint $table) {
            $table->index(['approver_id', 'valid_date_start', 'valid_date_end', 'is_delete'], 't005_avvi_index');
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
            $table->dropIndex('t005_avvi_index');
        });
    }
}
