<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM03636agreementMaxTimeColumn36ToThirtysix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->renameColumn('36agreement_max_time_id', 'thirtysix_agreement_max_time_id');
            $table->renameColumn('36agreement_id', 'thirtysix_agreement_id');
            $table->renameColumn('36agreement_aggregate_class_id', 'thirtysix_agreement_aggregate_class_id');
            $table->renameColumn('36agreement_aggregate_unit', 'thirtysix_agreement_aggregate_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m036_36agreement_max_time', function (Blueprint $table) {
            $table->renameColumn('thirtysix_agreement_max_time_id', '36agreement_max_time_id');
            $table->renameColumn('thirtysix_agreement_id', '36agreement_id');
            $table->renameColumn('thirtysix_agreement_aggregate_class_id', '36agreement_aggregate_class_id');
            $table->renameColumn('thirtysix_agreement_aggregate_unit', '36agreement_aggregate_unit');
        });
    }
}
