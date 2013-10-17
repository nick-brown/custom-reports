<?php

use Illuminate\Database\Migrations\Migration;

class CreateGaCompletions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ga_completions', function($table) {
		    $table->increments('id');
            $table->integer('pageviews');
            $table->integer('uniquePageviews');
            $table->string('customVarValue1', 255);
            $table->string('customVarValue2', 255);
            $table->string('hostname', 50);
            $table->string('country', 50);
            $table->string('region', 50);
            $table->string('city', 50);
			$table->float('latitutde', 8, 4);
            $table->float('longitude', 8, 4);
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
		Schema::dropIfExists('ga_completions');	
	}

}
