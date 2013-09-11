<?php namespace PeachSchnapps\Bandlands\Provider;

use Marvelley\Lastfm\Api\LastfmApiClient as LFM;
use PeachSchnapps\Bandlands\Datasource;

class LastfmProvider extends Datasource {
	public $type = 'lastfm';
	public $hosts = array('last.fm');
	public $channel = false;

	public function getListenersAttribute() {
		return !empty($this->data) ? $this->data->listeners : 0;
	}

	public function getProviderAttribute() {
		return ProviderStore::where('type', $this->type)->where('query', json_encode($this->query))->first();
	}

	public function getUsernameAttribute() {
		$url = $this->channel->site->url;
		if (preg_match('#.*last.fm/music/([^/?]+)#i', $url, $username)) {
			return $username[0];
		}
		return false;
	}

	public function getQueryAttribute() {
		return $this->username;
	}

	public function runTask($name) {
		switch($name) {
			case 'freshen':
			default:
				$code = -1;
				$message = "Last.fm Provider is Work in Progress";
				$query = $this->query;
				$l = LFM::factory(array('api_key' => 'f3a319713ea75695de8792b645820977'));
				$result = $l->getCommand('artist.getInfo', array(
				    'artist' => $this->query, 
				    "format" => "json"
				));
				$result = json_decode($result);
				if ($this->update_data($result))
				{
					$code = 1;
					$message = 'Success!';
				} else {
					$code = -100;
					$message = 'Problem saving '.json_encode($provider);
				}

			break;
		}
		if (!empty($code)) {
			$response['code'] = $code;
		}

		if (!empty($message)) {
			$response['message'] = $message;
		}

		return $response;
	}
}