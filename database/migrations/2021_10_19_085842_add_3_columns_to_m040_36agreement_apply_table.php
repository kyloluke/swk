<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add3ColumnsToM04036agreementApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->after('thirtysix_agreement_apply_class');
            $table->integer('is_valid')->default(0)->after('company_id');
            $table->unsignedBigInteger('origin_thirtysix_agreement_apply_id')->nullable()->after('is_valid');
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
            $table->dropColumn('company_id');
            $table->dropColumn('is_valid');
            $table->dropColumn('origin_thirtysix_agreement_apply_id');
        });
    }
}
