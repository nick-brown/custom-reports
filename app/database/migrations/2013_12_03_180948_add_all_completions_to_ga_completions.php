<?php

use Illuminate\Database\Migrations\Migration;

class AddAllCompletionsToGaCompletions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ga_completions', function($table)
        {
            $table->integer('goalCompletionsAll');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ga_completions', function($table)
        {
            $table->dropColumn('goalCompletionsAll');
        });
    }
}
