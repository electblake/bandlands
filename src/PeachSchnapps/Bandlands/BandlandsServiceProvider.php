<?php namespace PeachSchnapps\Bandlands;

use Illuminate\Support\ServiceProvider;

class BandlandsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('peach-schnapps/bandlands');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Shortcut so developers don't need to add an Alias in app/config/app.php
    // $this->app->booting(function()
    // {
    //   $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    //   $loader->alias('Bandlands', '\PeachSchnapps\Bandlands\Bandlands');
    // });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		// return array('bandlands');
	}

}