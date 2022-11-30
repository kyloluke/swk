<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterM009DisplayColorTableCreatedUserUpdatedUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m009_display_color', function (Blueprint $table) {
            $table->renameColumn('create_user', 'created_user');
            $table->renameColumn('update_user', 'updated_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m009_display_color', function (Blueprint $table) {
            $table->renameColumn('created_user', 'create_user');
            $table->renameColumn('updated_user', 'update_user');
        });
    }
}
