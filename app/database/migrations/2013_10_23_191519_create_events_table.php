<?php

use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ga_events', function($table)
        {
		    $table->increments('id');
            $table->integer('totalEvents');
            $table->string('customVarValue1', 255);
            $table->string('customVarValue2', 255);
            $table->string('hostname', 50);
            $table->string('country', 50);
            $table->string('region', 50);
            $table->string('city', 50);
            $table->string('eventCategory', 100);
            $table->timestamps();
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('ga_events'); 
	}

}
