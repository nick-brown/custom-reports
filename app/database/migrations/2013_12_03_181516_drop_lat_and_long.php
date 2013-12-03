<?php

use Illuminate\Database\Migrations\Migration;

class DropLatAndLong extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('ga_completions', function($table)
        {
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
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
            $table->float('latitutde', 8, 4);
            $table->float('longitude', 8, 4);
        });
	}

}