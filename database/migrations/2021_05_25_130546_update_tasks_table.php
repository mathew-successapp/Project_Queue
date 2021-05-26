<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('id')->change();
            $table->text('description')->nullable()->change();
            $table->date('due_date')->nullable()->change();
            $table->renameColumn('assigned_to', 'assignee_id');
            $table->string('project_id',50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('description');
            $table->dropColumn('due_date');
            $table->dropColumn('assignee_id');
            $table->dropColumn('project_id');
        });
    }
}
