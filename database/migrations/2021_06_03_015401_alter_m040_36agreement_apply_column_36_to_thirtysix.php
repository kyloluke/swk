<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM04036agreementApplyColumn36ToThirtysix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->renameColumn('36agreement_apply_id', 'thirtysix_agreement_apply_id');
            $table->renameColumn('36agreement_apply_name', 'thirtysix_agreement_apply_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->renameColumn('thirtysix_agreement_apply_id', '36agreement_apply_id');
            $table->renameColumn('thirtysix_agreement_apply_name', '36agreement_apply_name');
        });
    }
}
