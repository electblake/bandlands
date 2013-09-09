<?php namespace PeachSchnapps\Bandlands;

use Illuminate\Support\Contracts\JsonableInterface;
use Illuminate\Support\Contracts\ArrayableInterface;

class Datasource implements ArrayableInterface, JsonableInterface{

	public $type = null;
	public $url = null;
	public $attributes = array();

	public function __construct($url) {
		$this->url = $url;
	}

	/**
	 * Public
	 */	
	public function is_valid() {
		return $this->valid_url($this->url);
	}

	public function discover_url($url){}
	/**
	 * Convert the model instance to JSON.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Convert the model instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->attributes;
	}

	/**
	 * Privates
	 */

	/**
	 * Accept a url and parse whether
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	private function valid_url($url) {
		$url = parse_url($url);
		$host = $url['host'];
		if (stripos($host, $this->type)) {
			return true;
		}
		return false;
	}
}