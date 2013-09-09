<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateProviderStoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('band_provider_stores', function(Blueprint $table) {
			$table->string('id', 36)->primary();
			$table->string('type');
			$table->string('query');
			$table->text('result');
			$table->timestamp('refreshed_at');
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
		Schema::drop('band_provider_stores');
	}

}