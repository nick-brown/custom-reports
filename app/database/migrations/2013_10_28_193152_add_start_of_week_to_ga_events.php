<?php

use Illuminate\Database\Migrations\Migration;

class AddStartOfWeekToGaEvents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('ga_events', function($table)
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
        Schema::table('ga_events', function($table)
        {
            $table->dropIfExists('start_of_week');
        });
	}
}
