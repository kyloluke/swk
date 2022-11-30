<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterT003AttendanceAggregateColumn36ToThirtysix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->renameColumn('36agreement_special_provisions_apply_class', 'thirtysix_agreement_special_provisions_apply_class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t003_attendance_aggregate', function (Blueprint $table) {
            $table->renameColumn('thirtysix_agreement_special_provisions_apply_class', '36agreement_special_provisions_apply_class');
        });
    }
}
