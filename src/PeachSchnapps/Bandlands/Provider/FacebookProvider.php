<?php namespace PeachSchnapps\Bandlands\Provider;

use Carbon\Carbon;
use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Boparaiamrit\Facebook\FacebookFacade as FB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
class FacebookProvider extends \PeachSchnapps\Bandlands\Datasource {
	public $type = 'facebook';
	public $channel = false;

	public function getFansAttribute() {
		return $this->data->likes;
	}

	public function getListenersAttribute() {
		return 0;
	}

	public function getQueryAttribute() {
		return $this->username;
	}

	public function getProviderAttribute() {
		return ProviderStore::where('type', $this->type)->where('query', $this->query)->first();
	}
	public function getDataAttribute() {
		return json_decode($this->provider->result);
	}

	public function getUsernameAttribute() {
		$url = $this->channel->site->url;
		if (preg_match('#.*facebook.com/([^/?]+)#i', $url, $username)) {
			return $username[0];
		}
		return false;
	}

	public function runTask($name) {
		switch($name) {
			case 'freshen':
			default:
				$query = $this->query;
				$provider = $this->provider;

				$page_info = FB::api($query);
				$result = json_encode($page_info);

				if (empty($provider->id)) {
					$provider = array(
						'id' => Uuid::uuid1(),
						'type' => $this->type,
						'query' => $query,
						'result' => $result,
						'refreshed_at' => Carbon::now()
					);

					$provider = new ProviderStore($provider);
				} else {
					$provider->result = $result;
					$provider->refreshed_at = Carbon::now();
				}

				if ($provider->save())
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