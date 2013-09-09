<?php
namespace PeachSchnapps\Bandlands;

use FilesystemIterator;
use Symfony\Component\Finder\Finder;
class DatasourceFactory {

	static function from_channel($channel) {
		$url = $channel->site->url;
		$source = self::from_url($url);
		if (!empty($source)) {
			$source->channel = $channel;
		}
		return $source;
	}

	static function from_url($url) {
		foreach (self::get_registered() as $className) {
			$provider = "PeachSchnapps\Bandlands\Provider\\".$className;
			$source = new $provider($url);
			if ($source->is_valid()) {
				return $source;
			}
		}
		return false;
	}
	static function get_registered() {
		$files = Finder::create()->files()->in(__DIR__.'/Provider');
		$classes = array();
		foreach ($files as $file) {
			$class = $file->getBasename('.php');
			$classes[strtolower($class)] = $class;
		}
		return $classes;
	}
}