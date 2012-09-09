<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>
			
	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
		<h2>Edit profile</h2>
	
		<div class="row">
		
			<div class="span8">
				
				<% include ActionMessage %>
				$Content
				$EditForm
				$Form
				$PageComments
			</div>
		
			<div class="span4">
				<% control CurrentMember %>

					<h3>Facebook</h3>
					<% if IsLinkedToFacebook %>
						<p>Account is currently linked to facebook</p>
						<a href="$UnlinkFacebookLink">Unlink from facebook</a>
					<% else %>
						<p>Account not yet linked to facebook</p>
						<a href="$UnlinkTwitterLink">Link to facebook</a>
					<% end_if %>


					<h3>Twitter</h3>
					<% if IsLinkedToTwitter %>
						<p>Account is currently linked to twitter</p>
						<a href="$LinkAccountWithFacebookURL">Unlink from twitter</a>
					<% else %>
						<p>Account not yet linked to twitter</p>
						<a href="$LinkAccountWithTwitterURL">Link to twitter</a>
					<% end_if %>

				<% end_control %>
			</div>
		
		</div>
		
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>