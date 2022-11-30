<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM020HolidaySystemTableCreatedUserUpdatedUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m020_holiday_system', function (Blueprint $table) {
            $table->unsignedBigInteger('detail_no')->default(0);
            $table->unsignedinteger('is_delete')->default(0);
            $table->string('created_user',20)->default("0");
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_user',20)->default("0");
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m020_holiday_system', function (Blueprint $table) {
            $table->dropColumn('detail_no');
            $table->dropColumn('is_delete');
            $table->dropColumn('created_user');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_user');
            $table->dropColumn('updated_at');
        });
    }
}
