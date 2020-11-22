<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'votes',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->foreignId('project_id')->constrained();
                $table->enum('type', ['up', 'down', 'pig'])->default('up');
                $table->string('comment')->nullable();
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
            'votes',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['project_id']);
            }
        );
        Schema::dropIfExists('votes');
    }
}
