<?php

use Illuminate\Database\Migrations\Migration;

class CreateBandStatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('band_stats', function($table) {
			$stats = Band::$registered_stats;
			$table->string('id', 36)->primary();
			$table->string('band_id', 36);
			foreach ($stats as $name => $label) {
				$table->integer($name)->default(0);
			}
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
		Schema::drop('band_stats');
	}

}