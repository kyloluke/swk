<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM04036agreementApplyAddColumnThirtysixAgreementApplyClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m040_36agreement_apply', function (Blueprint $table) {
            $table->unsignedinteger('thirtysix_agreement_apply_class')->default(0)->after('thirtysix_agreement_apply_name');
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
            $table->dropColumn('thirtysix_agreement_apply_class');
        });
    }
}
