<?php
Output::set_title("User Administration");
MainTemplate::set_subtitle("View and edit user configuration settings");

if(!isset($_REQUEST["u"])) {
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

echo("<p><a href=\"?purge_expired_users\" class=\"btn btn-primary\">Purge expired users (those not in LDAP)</a></p>");

if(isset($_REQUEST["purge_expired_users"])) {
	$count = 0;
	$expired_users = DBDirectories::get_by_id(2624);

	foreach(Users::get_enabled() as $user) {

		if($user->is_ghost()) continue;

		$ldap = $user->get_ldap_attributes();
		if(!$ldap) {
			$count++;
			echo("Disabling user ".$user->get_username()." as they are not in LDAP at all...<br />");

			$user->set_enabled(false);
			$user->save();
			$folder = $user->get_user_folder();
			$folder->set_parent($expired_users);
			$folder->save();
		}
	}

	echo($count." users disabled.");
}

if(isset($_REQUEST["update_ex_members"])) {
	$count = 0;
	$expired_users = DBDirectories::get_by_id(2624);
	$ex_members = DBDirectories::get_by_id(2625);

	foreach(Users::get_enabled() as $user) {

		if($user->is_ghost()) continue;

		$ldap = $user->get_ldap_attributes();
		if(!$ldap) {
			$count++;
			echo("Disabling user ".$user->get_username()." as they are not in LDAP at all...<br />");

			$user->set_enabled(false);
			$user->save();
			$folder = $user->get_user_folder();
			$folder->set_parent($expired_users);
			$folder->save();
		} else if(isset($ldap["yearDisabled"]) && ($ldap["yearDisabled"][0] != '0')) {
			echo("Disabling user ".$user->get_username()." as they are an ex-member...<br/>");
			$count++;

			$user->set_enabled(false);
			$user->save();

			$folder = $user->get_user_folder();

			$year = $ex_members->find($ldap["yearDisabled"][0]);
			if(!$year) {
				$year = new DBDirectory;
				$year->set_name($ldap["yearDisabled"][0]);
				$year->set_parent($ex_members);
				$year->save();
			}

			$folder->set_parent($year);
			$folder->save();
		}
	}

	echo($count." users disabled.");
}
?>