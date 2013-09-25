<?php

switch($_REQUEST["action"]) {
	case "members":
		$members = array();
		foreach(Groups::get_by_id($_REQUEST["group"])->get_users() as $user) $members[$user->get_id()] = $user->get_username();
		for($i == 1; $i <= 4; $i++) unset($members[$i]); // don't include system, root, nobody, guest
		echo(json_encode($members));
		break;
	case "add-user":
		$group = Groups::get_by_id($_REQUEST["group"]);
		$user = Users::get_by_username($_REQUEST["user"]);
		if($user) {
			$group->add_user($user);
			echo(json_encode(array($user->get_id() => $user->get_username())));
		} else echo(json_encode(array(0,"")));
		break;
	case "del-user":
		$group = Groups::get_by_id($_REQUEST["group"]);
		$user = Users::get_by_id($_REQUEST["user"]);
		if($user) {
			$group->remove_user($user);
			echo(json_encode(array($user->get_id() => $user->get_username())));
		} else echo(json_encode(array(0,"")));
}

?>