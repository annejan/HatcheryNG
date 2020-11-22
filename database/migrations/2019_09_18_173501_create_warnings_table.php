<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'warnings',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('project_id')->constrained();
                $table->string('description');
                $table->softDeletes();
                $table->timestamps();
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
            'warnings',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['project_id']);
            }
        );
        Schema::dropIfExists('warnings');
    }
}
