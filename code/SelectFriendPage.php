<?php
class SelectFriendPage extends Page {

	static $db = array();

	static $has_one = array();

	//to do:
	//add icon
	//does this page have allowed children
	//how many can we create, etc....

}
class SelectFriendPage_Controller extends Page_Controller {
	
	public function init() {
		parent::init();
		Requirements::javascript("social_integration/javascripts/select_friends.js");
	}

	//to do: controller methods should be twitterfriends without the _ all lowercase
	function twitterFriends(){
		$member = Member::currentUser();
		//if(!$friends = Session::get('TwitterFriends')){
			$friends = $this->customise(array('TwitterFriends' => $member->TwitterFriends()))->renderWith('TwitterFriendsAjax');
		//	Session::set("TwitterFriends", $friends);
		//}
		return $friends;
	}

	//to do: controller methods should be twitterfriends without the _ all lowercase
	function facebookFriends(){
		$member = Member::currentUser();
		//if(!$friends = Session::get('FacebookFriends')){
			$friends = $this->customise(array('FacebookFriends' => $member->FacebookFriends()))->renderWith('FacebookFriendsAjax');
		//	Session::set("FacebookFriends", $friends);
		//}
		return $friends;
	}

	//to do: controller methods should be twitterfriends without the _ all lowercase
	function setMember(){
		//to do: do we need static method, use Convert::raw2sql
		Page::setFriend($_REQUEST['FriendName'], $_REQUEST['SocialID'], $_REQUEST['SocialNetwork']);
		Director::redirectBack();
	}

}
