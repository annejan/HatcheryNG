<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'projects',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained();
                $table->string('name')->unique();
                $table->softDeletes();
                $table->nullableTimestamps();
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
            'projects',
            function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            }
        );
        Schema::dropIfExists('projects');
    }
}
