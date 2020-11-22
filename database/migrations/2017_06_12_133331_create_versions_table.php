<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'versions',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained();
                $table->integer('revision')->unsigned();
                $table->string('dependencies');
                $table->string('zip');
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
            'versions',
            function (Blueprint $table) {
                $table->dropForeign(['project_id']);
            }
        );
        Schema::dropIfExists('versions');
    }
}
