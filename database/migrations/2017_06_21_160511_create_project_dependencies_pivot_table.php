<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectDependenciesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'dependencies',
            function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->constrained();
                $table->foreignId('depends_on_project_id')->nullable()->constrained('projects');
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
            'dependencies',
            function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->dropForeign(['depends_on_project_id']);
            }
        );
        Schema::dropIfExists('dependencies');
    }
}
