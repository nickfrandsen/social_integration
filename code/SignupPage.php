<?php
class SignupPage extends Page {

	static $db = array();

	static $has_one = array();

}

class SignupPage_Controller extends Page_Controller {

	function HideSocialSignup(){
		return isset($_REQUEST['HideSocialSignup']);
	}

	function signupWithTwitter(){
		if(empty($_REQUEST['oauth_token'])){
			Twitter::redirect_to_login_prompt('/signup/signupWithTwitter');
		}
		else {
			$access_token = Twitter::get_access_token();
			$user = Twitter::get_users_info($access_token);

			$member = new Member();
			list($member->FirstName, $member->Surname) = explode(" ", $user->name);
			$member->addTwitterCredentials($access_token);
			$member->login();
			$this->setMessage("Please add your email and password that you want to use for this site.");
			return Director::redirect("/signup/editUser");
		}
	}

	function signupWithFacebook(){
		if(!Facebook::signup_code_created()){
	   		Facebook::redirect_to_login_prompt('/signup/signupWithFacebook');
		}
	   	if(Facebook::csrf_checks_passed()) {
			$access_token = Facebook::get_access_token('/signup/signupWithFacebook');
			$user = Facebook::get_users_info($access_token);

			try {
				$member = new Member();
				$member->FirstName = $user->first_name;
				$member->Surname = $user->last_name;
				$member->Email = $user->email;
				$member->addFacebookCredentials($access_token, $user->id);
				$member->login();
								
				$this->setMessage("Please select a password.");
			} catch (ValidationException $e){
				$this->setMessage("Changes to profile could not be saved because email is already in use.", "bad");
				return Director::redirectBack();
			}

			return Director::redirect("/signup/editUser");
	   	}
	}

	function loginWithTwitter(){
		if(empty($_REQUEST['oauth_token'])){
			Twitter::redirect_to_login_prompt('/signup/loginWithTwitter');
		} else {
			/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

			$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

			$member = Member::get_one("Member", "TwitterUserID={$access_token['user_id']}");
			if($member){
				$member->login();
			} else {
				$this->setMessage("Could not find your account, are you sure you have already signed up?", "bad");
				return Director::redirectBack();
			}
			

			return Director::redirect('/');
		}
	}

	function loginWithFacebook(){
		if(!Facebook::signup_code_created()){
			Facebook::redirect_to_login_prompt('/signup/loginWithFacebook');
		}
		if(Facebook::csrf_checks_passed()) {
			$access_token = Facebook::get_access_token('/signup/loginWithFacebook');
			$user = Facebook::get_users_info($access_token);

			$member = Member::get_one("Member", "Email='{$user->email}'");
			if($member){
				$member->addFacebookCredentials($access_token);
				$member->login();
			} else {
				$this->setMessage("Could not find your account, are you sure you have already signed up?", "bad");
				return Director::redirectBack();
			}

			return Director::redirect('/');
	   	}
	}

	function linkAccountWithTwitter(){
		//TODO should only work if logged in, but what should it return if not?
		if(empty($_REQUEST['oauth_token'])){
			Twitter::redirect_to_login_prompt('/signup/linkAccountWithTwitter');
		}
		else {
			$access_token = Twitter::get_access_token();

			$member = Member::currentUser();
			$member->addTwitterCredentials($access_token);

			return Director::redirectBack();
		}
	}

	function linkAccountWithFacebook(){
		//TODO should only work if logged in, but what should it return if not?
		if(!Facebook::signup_code_created()){
			Facebook::redirect_to_login_prompt('/signup/linkAccountWithFacebook');
		}
		if(Facebook::csrf_checks_passed()) {
			$access_token = Facebook::get_access_token('/signup/linkAccountWithFacebook');

			$user = Facebook::get_users_info($access_token);
			$member = Member::currentUser();
			$member->addFacebookCredentials($access_token, $user->id);

			return Director::redirectBack();
	   	}
	}

	function unlinkAccountWithTwitter(){
		$member = Member::currentUser();
		$member->unlinkAccountWithTwitter();
		Director::redirectBack();
	}

	function unlinkAccountWithFacebook(){
		$member = Member::currentUser();
		$member->unlinkAccountWithFacebook();
		Director::redirectBack();
	}

	function editUser(){
		return array();
	}

	public function SignupForm(){
		$fields = SocialMemberDecorator::get_form_fields();
		$actions = new FieldSet(new FormAction('doSignup', 'Save changes'));
		$form = new Form($this, 'SignupForm', $fields, $actions);
		return $form;
	}

	public function doSignup($data, $form){
		$member = new Member();
		try {
			$form->saveInto($member);
			$member->write();
			$member->login();
			$this->setMessage("Signup successful.");
		} catch (ValidationException $e){
			$this->setMessage("Signup not successful because email is already in use.", "bad");
			return Director::redirectBack();
		}
		return Director::redirect('/');
	}

	public function EditForm(){
		$member = Member::currentUser();
		$fields = SocialMemberDecorator::get_form_fields();
		$actions = new FieldSet(new FormAction('doEdit', 'Save changes'));
		$form = new Form($this, 'EditForm', $fields, $actions);
		$form->loadDataFrom($member);
		return $form;
	}

	public function doEdit($data, $form){
		$member = Member::currentUser();
		try {
			$form->saveInto($member);
			$member->write();
			$this->setMessage("Changes to profile have been saved.");
		} catch (ValidationException $e){
			$this->setMessage("Changes to profile could not be saved because email is already in use.", "bad");
			return Director::redirectBack();
		}
		return Director::redirect('/');
	}

}
