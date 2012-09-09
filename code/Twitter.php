<?php
class Twitter {

	static function redirect_to_login_prompt($redirect_url){
		$redirect_url = "http://{$_SERVER['HTTP_HOST']}{$redirect_url}";
		
		/* Build TwitterOAuth object with client credentials. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

		/* Get temporary credentials. */
		$request_token = $connection->getRequestToken($redirect_url);

		/* Save temporary credentials to session. */
		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		/* If last connection failed don't display authorization link. */
		switch ($connection->http_code) {
		  case 200:
		    /* Build authorize URL and redirect user to Twitter. */
		    $url = $connection->getAuthorizeURL($token);
			return Director::redirect($url);
		    break;
		  default:
		    /* Show notification if something went wrong. */
			throw new Exception('Could not connect to Twitter. Refresh the page or try again later.');
		}
	}
	
	static function get_access_token(){
		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		/* Request access tokens from twitter */
		$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
		
		/* Remove no longer needed request tokens */
		unset($_SESSION['access_token']);
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		
		return $access_token;
	}
	
	static function get_users_info($access_token){
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		$profile = $connection->get('account/verify_credentials');
		
		return $profile;
	}
	
	static function get_list_of_friends($twitterUserID){
		if($twitterUserID){
			$url = "http://api.twitter.com/1/friends/ids.json?user_id={$twitterUserID}";
		} else {
			throw new Exception("No id is being passed to the twitter api.");
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlout = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($curlout, true);
		
		$ids = "";
		$count = 0;
		$array = array();
		foreach($response["ids"] as $friend){
			$ids .= "{$friend},";
			$count++;			
			if($count == 100){
				$array = array_merge($array, Twitter::get_list_of_fifty_friends($ids));
				$count = 0;
				$ids = "";
			}
		}
				
		if($count > 0){
			$array = array_merge($array, Twitter::get_list_of_fifty_friends($ids));
		}
		
		return $array;
	}
	
	static function get_list_of_fifty_friends($ids){
		$url = "http://api.twitter.com/1/users/lookup.json?user_id={$ids}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlout = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($curlout, true);
		
		return $response;
	}
	
}