<?php
class Facebook {

	static function signup_code_created(){
		return !empty($_REQUEST["code"]);
	}

	static function redirect_to_login_prompt($redirect_url){
		$redirect_url = "http://{$_SERVER['HTTP_HOST']}{$redirect_url}";
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
		. FACEBOOK_APP_ID . "&redirect_uri=" . urlencode($redirect_url) . "&state="
		. $_SESSION['state'] . "&scope=email";
		return Director::redirect($dialog_url);
	}

	static function csrf_checks_passed(){
		return ( isset($_SESSION['state']) && isset($_REQUEST['state']) && ($_SESSION['state'] === $_REQUEST['state']) );
	}

	static function get_access_token($redirect_url){
		$redirect_url = "http://{$_SERVER['HTTP_HOST']}{$redirect_url}";
		$code = $_REQUEST["code"];
		$token_url = "https://graph.facebook.com/oauth/access_token?"
       		. "client_id=" . FACEBOOK_APP_ID . "&redirect_uri=" . urlencode($redirect_url)
       		. "&client_secret=" . FACEBOOK_APP_SECRET . "&code=" . $code;

     	$response = file_get_contents($token_url);
     	$params = null;
     	parse_str($response, $params);

		return $params['access_token'];
	}

	static function get_users_info($access_token){
		$graph_url = "https://graph.facebook.com/me?access_token=". $access_token;
		return json_decode(file_get_contents($graph_url));
	}

	static function get_list_of_friends($access_token){
		$url = "https://graph.facebook.com/me/friends?fields=id,name,picture&access_token={$access_token}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlout = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($curlout, true);

		return $response['data'];
	}

}
