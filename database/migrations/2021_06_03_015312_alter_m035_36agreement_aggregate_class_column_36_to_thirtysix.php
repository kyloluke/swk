<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03536agreementAggregateClassColumn36ToThirtysix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->renameColumn('36agreement_aggregate_class_id', 'thirtysix_agreement_aggregate_class_id');
            $table->renameColumn('36agreement_aggregate_class', 'thirtysix_agreement_aggregate_class');
            $table->renameColumn('36agreement_aggregate_class_name', 'thirtysix_agreement_aggregate_class_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m035_36agreement_aggregate_class', function (Blueprint $table) {
            $table->renameColumn('thirtysix_agreement_aggregate_class_id', '36agreement_aggregate_class_id');
            $table->renameColumn('thirtysix_agreement_aggregate_class', '36agreement_aggregate_class');
            $table->renameColumn('thirtysix_agreement_aggregate_class_name', '36agreement_aggregate_class_name');
        });
    }
}
