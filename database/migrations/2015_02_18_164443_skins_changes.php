<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkinsChanges extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('skins', function($table)
		{
		    //$table->date('released_on')->nullable()->change();
		    DB::statement("ALTER TABLE skins MODIFY released_on DATE null;");
		    $table->string('skin_set');
		    $table->date('last_sale');
		    $table->date('next_sale');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('skins', function($table)
		{
			$table->date('released_on')->change();
		    $table->dropColumn('skin_set');
		    $table->dropColumn('last_sale');
		    $table->dropColumn('next_sale');
		});
	}

}
