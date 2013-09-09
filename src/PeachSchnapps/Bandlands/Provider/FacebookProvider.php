<?php namespace PeachSchnapps\Bandlands\Provider;

use Boparaiamrit\Facebook\FacebookFacade as FB;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class FacebookProvider extends \PeachSchnapps\Bandlands\Datasource {
	public $type = 'facebook';
	public $channel = false;

	public function runTask($name) {
		switch($name) {
			case 'freshen':
			default:
				$query = $this->getUsername();
				$page_info = FB::api($query);

				$provider = array(
					'type' => $this->type,
					'query' => $query,
					'result' => serialize($page_info),
					'refreshed_at' => Carbon::now()
				);

				$provider = new ProviderStore($provider);

				if ($provider->save())
				{
					return array('code' => 1, 'message' => 'Success!');
				} else {
					return array('code' => -100, 'message' => 'Problem saving '.serialize($provider));
				}
			break;
		}
	}

	public function getUsername() {
		$url = $this->channel->site->url;
		if (preg_match('#.*facebook.com/([^/?]+)#i', $url, $username)) {
			return $username[0];
		}
		return false;
	}

	public function getSiteAttribute() {
		return $this->channel->site;
	}
}