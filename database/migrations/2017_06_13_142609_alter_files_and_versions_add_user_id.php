<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFilesAndVersionsAddUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'versions',
            function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained();
            }
        );
        Schema::table(
            'files',
            function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'versions',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        );
        Schema::table(
            'files',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        );
    }
}
