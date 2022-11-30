<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM007EmployeeColumn36ToThirtysix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->renameColumn('36_agreement_apply_id', 'thirtysix_agreement_apply_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m007_employee', function (Blueprint $table) {
            $table->renameColumn('thirtysix_agreement_apply_id', '36_agreement_apply_id');
        });
    }
}
