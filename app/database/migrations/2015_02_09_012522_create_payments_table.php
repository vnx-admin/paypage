<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('payments', function($table) {
			$table->increments('id');
			$table->integer('order');
			$table->integer('value');
			$table->integer('currency_id');
			$table->string('cardnum',16);
			$table->string('valid',5);
			$table->string('name');
			$table->integer('cv');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
			
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('payments');
	}

}
