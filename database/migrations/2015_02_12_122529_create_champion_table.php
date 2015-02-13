<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChampionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('champions', function($table)
		{
		    $table->increments('id');
		    $table->string('name')->unique();
		    $table->integer('ip');
		    $table->integer('rp');
		    $table->date('released_on')->nullable;
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
		Schema::drop('champions');
	}

}
