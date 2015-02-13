<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkinTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('skins', function($table)
		{
		    $table->increments('id');
		    $table->integer('champion_id')->unsigned();
		    $table->string('champion_name');
		    $table->string('skin_name')->unique();
		    $table->integer('rp');
		    $table->date('released_on')->nullable;
		    $table->timestamps();
		    $table->foreign('champion_id')->references('id')->on('champions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('skins');
	}

}
