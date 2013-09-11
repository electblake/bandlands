<?php namespace PeachSchnapps\Bandlands;

use Carbon\Carbon;
use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use PeachSchnapps\Bandlands\Provider\ProviderStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Contracts\JsonableInterface;
use Illuminate\Support\Contracts\ArrayableInterface;

class Datasource implements ArrayableInterface, JsonableInterface{

	public $type = null;
	public $hosts = array();
	public $attributes = array();
	public $url = null;
	public $site = null;
	public $channel = false;

	public function __construct($url) {
		$this->url = $url;
	}

	public function getProviderAttribute() {
		return ProviderStore::where('type', $this->type)->where('query', json_encode($this->query))->first();
	}

	public function getDataAttribute() {
		$provider = $this->provider;
		if (!empty($provider)) {
			return json_decode($provider->result);
		}
		return false;
	}

	/**
	 * Public
	 */	

	public function update_data($result) {
		$provider = $this->provider;

		if (empty($provider->id)) {
			$provider = array(
				'id' => Uuid::uuid1(),
				'type' => $this->type,
				'query' => json_encode($this->query),
				'result' => json_encode($result),
				'refreshed_at' => Carbon::now()
			);
			$provider = new ProviderStore($provider);
		} else {
			$provider->result = json_encode($result);
			$provider->refreshed_at = Carbon::now();
		}
		return $provider->save();
	}

	public function is_valid() {
		return $this->validate_url($this->url);
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
	 * Accept a url and parse whether
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	public function validate_url($url) {
		$url = parse_url($url);
		$url_host = $url['host'];
		foreach ($this->hosts as $host) {
			if (stripos($url_host, $host)) {
				return true;
			}
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