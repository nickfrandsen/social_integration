<div id="LoginPage">
	
	<div class="row">
	
		<% if HideSocialSignup %>
		<% else %>
			<div class="span6 well">

				<h2>Signup with social network</h2>
				<% include ActionMessage %>
				$Content
				<ul class="unstyled">
					<li>
						<a href="/signup/signupWithFacebook" class="btn btn-large btn-primary span7">
						<i class="icon-facebook"></i>Signup with facebook</a>
					</li>
					<li>
						<a href="/signup/signupWithTwitter" class="btn btn-large btn-primary span7">
						<i class="icon-twitter"></i>&nbsp; Signup with twitter</a>
					</li>
				</ul>
	
			</div>
		<% end_if %>
	
		<div class="span6 well">

			<h2><% if HideSocialSignup %>Sign up using email<% else %>Or, just use email<% end_if %></h2>
			<p>Log in met je emailadres en wachtwoord</p>
			$SignupForm
	
		</div>
	
	</div>
	
</div>