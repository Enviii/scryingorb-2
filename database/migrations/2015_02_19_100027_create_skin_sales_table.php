<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkinSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('skin_sales', function($table)
		{
		    $table->increments('id');
		    $table->integer('champion_id')->unsigned();
		    $table->integer('skin_id')->unsigned();
		    $table->boolean('active');
		    $table->date('start_date');
		    $table->date('end_date');
		    $table->integer('original_price')->unsigned();
		    $table->integer('sale_price')->unsigned();
		    $table->timestamps();

		    $table->foreign('champion_id')->references('id')->on('champions');
		    $table->foreign('skin_id')->references('id')->on('skins');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('skin_sales');
	}

}
