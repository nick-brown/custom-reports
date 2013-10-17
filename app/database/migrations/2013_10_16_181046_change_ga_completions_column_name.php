<?php

use Illuminate\Database\Migrations\Migration;

class ChangeGaCompletionsColumnName extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ga_completions', function($table)
		{
			$table->renameColumn('latitutde', 'latitude');
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
			$table->renameColumn('latitude', 'latitutde');
		});
	}

}
