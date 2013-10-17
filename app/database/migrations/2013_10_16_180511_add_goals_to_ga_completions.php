<?php

use Illuminate\Database\Migrations\Migration;

class AddGoalsToGaCompletions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ga_completions', function($table)
		{
			$table->integer('goal16Completions');
			$table->integer('goal14Completions');
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
			$table->dropColumn('goal16Completions');
			$table->dropColumn('goal14Completions');
		});
	}
}
