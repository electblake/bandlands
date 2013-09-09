<?php namespace PeachSchnapps\Bandlands;

use Illuminate\Database\Eloquent\Collection;

class Discover {
	public function __construct($url) {
		return self::url($url);
	}

	static function url($sites) {
		$urls = iterator_to_array($sites);

		foreach ($urls as $i => $site) {

			if (is_object($site) && !empty($site->url)) {
				$url = $site->url;
			} else {
				$url = $site;
			}

			$datasource = DatasourceFactory::from_url($url);
			if (!empty($datasource)) {
				$datasource->site = $site;
				$urls[$i] = $datasource;
			} else {
				unset($urls[$i]);
			}
		}

		return new Collection($urls);
	}

}