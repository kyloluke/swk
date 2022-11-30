<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03436agreementColumn36ToThirtysix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->renameColumn('36agreement_id', 'thirtysix_agreement_id');
            $table->renameColumn('36agreement_apply_id', 'thirtysix_agreement_apply_id');
            $table->renameColumn('36agreement_special_provisions_max_count', 'thirtysix_agreement_special_provisions_max_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m034_36agreement', function (Blueprint $table) {
            $table->renameColumn('thirtysix_agreement_id', '36agreement_id');
            $table->renameColumn('thirtysix_agreement_apply_id', '36agreement_apply_id');
            $table->renameColumn('thirtysix_agreement_special_provisions_max_count', '36agreement_special_provisions_max_count');
        });
    }
}
