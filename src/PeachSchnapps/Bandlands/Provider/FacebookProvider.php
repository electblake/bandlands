<?php namespace PeachSchnapps\Bandlands\Provider;
use Boparaiamrit\Facebook;
class FacebookProvider extends \PeachSchnapps\Bandlands\Datasource {
	public $type = 'facebook';
	public $channel = false;

	public function runTask($name) {
		switch($name) {
			case 'freshen':
			default:
				$page_info = Facebook::api($this->username);
				return $page_info;
				// return array(true);
			break;
		}
	}

	public function getUsernameAttribute() {
		$url = $this->site->url;
		if (preg_match('#.*facebook.com/([^/?]+)#i', $url, $username)) {
			return $username[0];
		}
		return false;
	}

	public function getSiteAttribute() {
		return $this->channel->site;
	}
}