<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('currencies', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->string('sign',2);
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
		});
		
		$users = DB::table('currencies')->insert(array(
			array('name' => 'rub','sign' => '₽'),
			array('name' => 'usd','sign' => '$')
		));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('currencies');
	}

}
