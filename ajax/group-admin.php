<?php

switch($_REQUEST["action"]) {
	case "members":
		$members = array();
		foreach(Groups::get_by_id($_REQUEST["group"])->get_users() as $user) $members[$user->get_id()] = $user->get_username();
		for($i == 1; $i <= 4; $i++) unset($members[$i]); // don't include system, root, nobody, guest
		echo(json_encode($members));
		break;
}

?>