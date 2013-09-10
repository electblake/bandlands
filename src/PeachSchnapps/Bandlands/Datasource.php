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
	
	public function stat($name) {
		if ($value = $this->$name) {
			return $value;
		}
		return 0;
	}
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

	  /**
   * Adds support for $this->get_varname virtual properties
   * (Implementation sort of mirrors what I originally grabbed from PHP ActiveRecord)
   * 
   * @param  string $name looks for $this->get_$name() to generate $this->name value
   */
  public function __get($name) {
    $method = 'get'.studly_case($name.'Attribute');
    if (method_exists($this, $method)) {
      return call_user_func(array(&$this, $method));
    }
  }
  /**
   * Adds support for $this->set_varname to preprocess property settings
   * (Implementation sort of mirrors what I originally grabbed from PHP ActiveRecord)
   * 
   * @param  string $name looks for $this->set_$name() to help set value
   */
  public function __set($name, $val) {
    $method = 'set'.studly_case($name.'Attribute');
    if (method_exists($this, $method)) {
      return call_user_func(array(&$this, $method), $val);
    }
  }
}