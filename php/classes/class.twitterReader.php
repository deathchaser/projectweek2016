<?php

class TwitterReader {

	private $file;
	private $requestUrl = 'https://ba4f9524-07de-4ac5-82e3-58af008724e2:0gssliiq3f@cdeservice.mybluemix.net/api/v1/messages/search';

	/**
	 * get twitter feed
	 * @param  String $hashtag the hashtag to search for
	 * @return Object          JSON objects
	 */
	public function getFeed($hashtag) {
		$content = file_get_contents($requestUrl . '?q=#' . $hashtag);
		return json_decode($content);
	}

}

?>