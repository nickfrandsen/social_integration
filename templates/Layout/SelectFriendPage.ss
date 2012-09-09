<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>
			
	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
	
		<% control Member %>
		
			<% include SelectFriend %>
		
		<% end_control %>
		

		$Content
		$Form
		$PageComments
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>