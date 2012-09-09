<?php
class SocialMemberDecorator extends DataObjectDecorator {

	function extraStatics() { 
		return array ( 
			'db' => array(
				'FacebookUserID' => 'Int',
				'FacebookOAuthToken' => 'Text',
				'TwitterUserID' => 'Int',
				'TwitterOAuthToken' => 'Text',
				'TwitterOAuthSecret' => 'Text'
			) 
		); 
	}
	
	static function get_form_fields() {
		return new FieldSet(
			new TextField('FirstName', 'First Name'),
			new TextField('Surname', 'Last Name'),
			new TextField('Email', 'Email'),                  
			new ConfirmedPasswordField('Password', 'Password *')
		);
	}

	//function updateCMSFields(&$fields) { 
	//	$fields->addFieldToTab("Root.Avatar", new ImageField('Avatar')); 
	//} 
	
	function TwitterFriends(){
		$friends = Twitter::get_list_of_friends($this->owner->TwitterUserID);
				
		$set = new DataObjectSet();
		foreach($friends as $friend){
			$set->push(new ArrayData(array(
				'ID' => $friend["id"],
				'Name' => $friend["name"],
				'PictureURL' => $friend["profile_image_url"]
			)));
		}
		return $set;
	}
	
	function FacebookFriends(){		
		$friends = Facebook::get_list_of_friends($this->owner->FacebookOAuthToken);
		
		$set = new DataObjectSet();
		foreach($friends as $friend){
			$set->push(new ArrayData(array(
				'ID' => $friend["id"],
				'Name' => $friend["name"],
				'PictureURL' => $friend["picture"]['data']['url']
			)));
		}
		return $set;
	}
	
	function unlinkAccountWithTwitter(){
		$this->owner->TwitterUserID = NULL;
		$this->owner->TwitterOAuthToken = NULL;
		$this->owner->TwitterOAuthSecret = NULL;
		$this->owner->write();
	}
	
	function unlinkAccountWithFacebook(){
		$this->owner->FacebookUserID = NULL;
		$this->owner->FacebookOAuthToken = NULL;
		$this->owner->write();
	}
	
	function UnlinkTwitterLink(){
		return "/signup/unlinkAccountWithTwitter";
	}
	
	function UnlinkFacebookLink(){
		return "/signup/unlinkAccountWithFacebook";
	}
	
	function LinkAccountWithTwitterURL(){
		return "/signup/linkAccountWithTwitter";
	}
	
	function LinkAccountWithFacebookURL(){
		return "/signup/linkAccountWithFacebook";
	}
	
	function IsLinkedToTwitter(){
		return !$this->NotLinkedToTwitter();
	}
	
	function IsLinkedToFacebook(){
		return !$this->NotLinkedToFacebook();
	}
	
	function NotLinkedToTwitter(){
		return empty($this->owner->TwitterOAuthToken);
	}
	
	function NotLinkedToFacebook(){
		return empty($this->owner->FacebookOAuthToken);
	}
	
	function addTwitterCredentials($access_token){
		if(!isset($access_token['user_id']) && !isset($access_token['oauth_token']) && !isset($access_token['oauth_token_secret'])) { 
			throw new Exception("Please make sure access token is an array with the correct params"); 
		}
		$this->owner->TwitterUserID = $access_token['user_id'];
		$this->owner->TwitterOAuthToken = $access_token['oauth_token'];
		$this->owner->TwitterOAuthSecret = $access_token['oauth_token_secret'];
		$this->owner->write();
	}
	
	function addFacebookCredentials($access_token, $id = false){
		if(!is_string($access_token)){
			throw new Exception("Should be a simple string containing the facebook access token");
		}
		if($id){ $this->owner->FacebookUserID = $id; }
		$this->owner->FacebookOAuthToken = $access_token;
		$this->owner->write();
	}
	
}