<?php namespace PeachSchnapps\Bandlands\Provider;

use Boparaiamrit\Facebook\FacebookFacade as FB;
use PeachSchnapps\Bandlands\Datasource;

class FacebookProvider extends Datasource {
	public $type = 'facebook';
	public $hosts = array('facebook.com');

	public function getFansAttribute() {
		if (is_object($this->data)) {
			return $this->data->likes;
		}
		return 0;
	}

	public function getListenersAttribute() {
		return 0;
	}

	public function getQueryAttribute() {
		return $this->username;
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
				$result = FB::api($query);
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