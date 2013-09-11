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
				// $message = "Last.fm Provider is Work in Progress";
				$query = $this->query;
				// $provider = $this->provider;

				// $page_info = FB::api($query);
				// $result = json_encode($page_info);

				// if (empty($provider->id)) {
				// 	$provider = array(
				// 		'id' => Uuid::uuid1(),
				// 		'type' => $this->type,
				// 		'query' => $query,
				// 		'result' => $result,
				// 		'refreshed_at' => Carbon::now()
				// 	);

				// 	$provider = new ProviderStore($provider);
				// } else {
				// 	$provider->result = $result;
				// 	$provider->refreshed_at = Carbon::now();
				// }

				// if ($provider->save())
				// {
				// 	$code = 1;
				// 	$message = 'Success!';
				// } else {
				// 	$code = -100;
				// 	$message = 'Problem saving '.json_encode($provider);
				// }

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