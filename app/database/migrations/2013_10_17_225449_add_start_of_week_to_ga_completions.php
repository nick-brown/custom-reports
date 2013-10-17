<?php

use Illuminate\Database\Migrations\Migration;

class AddStartOfWeekToGaCompletions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('ga_completions', function($table)
        {
            $table->date('start_of_week');
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
            $table->dropIfExists('start_of_week');
        });
	}

}
