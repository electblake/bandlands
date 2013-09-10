<?php namespace PeachSchnapps\Bandlands\Provider;

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ProviderStore extends Eloquent {

	protected $table = 'band_provider_stores';
	protected $guarded = array();

	static function boot() {
		// if (empty(self::$attributes['id'])) {
		// 	$attributes['id'] = Uuid::uuid1();
		// }
	}

}