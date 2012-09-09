<ul class="nav nav-tabs">
  <li><a href="#facebook_tab" data-toggle="tab">Facebook</a></li>
  <li><a href="#twitter_tab" data-toggle="tab">Twitter</a></li>
  <li><a href="#linkedin_tab" data-toggle="tab">Linkedin</a></li>
  <li><a href="#email_tab" data-toggle="tab">Email</a></li>
</ul>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="facebook_tab">
	
		<h2>Facebook friends</h2>
		<% if IsLinkedToFacebook %>
			<p>Enter name</p>
			<form action="/select-friend/setMember">
				<img src="" width="26" height="26" id="facebook_search_img" />
				<input type="text" id="search_for_facebook_friends" name="FriendName" />
				<input type="hidden" id="selected_facebook_id" name="SocialID" />
				<input type="hidden" name="SocialNetwork" value="Facebook" />
				<input type="submit" value="send gift" />
			</form>
			<div id="facebook_friends">
				<div class="loading_gif">
					<img src="$Top.ThemeDir/images/loading.gif" />
					<p>Loading facebook friends...</p>
				</div>			
			</div>
		<% else %>
			<p>Account not yet linked to facebook</p>
			<a href="$LinkAccountWithFacebookURL">Link to facebook</a>
		<% end_if %>
		
    </div>
    <div class="tab-pane fade" id="twitter_tab">
		
		<h2>Twitter friends</h2>
		<% if IsLinkedToTwitter %>
			<p>Enter name</p>
			<form action="/select-friend/setMember">
				<img src="" width="26" height="26" id="twitter_search_img" />
				<input type="text" id="search_for_twitter_friends" name="FriendName" />
				<input type="hidden" id="selected_twitter_id" name="SocialID" />
				<input type="hidden" name="SocialNetwork" value="Twitter" />
				<input type="submit" value="send gift" />
			</form>
			<div id="twitter_friends">
				<div class="loading_gif">
					<img src="$Top.ThemeDir/images/loading.gif" />
					<p>Loading twitter friends...</p>
				</div>
			</div>
		<% else %>
			<p>Account not yet linked to twitter</p>
			<a href="$LinkAccountWithTwitterURL">Link to twitter</a>
		<% end_if %>
		
    </div>
    <div class="tab-pane fade" id="linkedin_tab">
		<h2>Not implemented yet..</h2>
    </div>
    <div class="tab-pane fade" id="email_tab">
		
		<h2>Email gift</h2>
		<p>Enter Email</p>
		<form action="/select-friend/setMember">
			<input type="text" name="FriendName" value="Name" />
			<input type="text" name="SocialID" value="Email" />
			<input type="hidden" name="SocialNetwork" value="Email" />
			<input type="submit" value="send gift" />
		</form>
		
    </div>
</div>