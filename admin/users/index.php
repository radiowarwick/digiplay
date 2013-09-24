<?php
Output::set_title("User Administration");
MainTemplate::set_subtitle("View and edit user configuration settings");

if(!$_REQUEST["u"]) {
	echo("<h4>Enter a username:</h4>
	<form action=\"#\" method=\"GET\" class=\"form-horizontal\">
		<div class=\"form-group\">
			<div class=\"col-sm-4\">
				<input type=\"text\" placeholder=\"Username...\" name=\"u\" class=\"form-control\">
			</div>
			<input type=\"submit\" class=\"btn btn-primary col-sm-2\" value=\"Search\">
		</div>
	</form>");
} else {
	$user = Users::get_by_username($_REQUEST["u"]);
	var_dump($user);
	var_dump($user->get_ldap_attributes());
	var_dump($user->get_groups());
}
?>