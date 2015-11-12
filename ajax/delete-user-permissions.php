<?php

if(Session::is_user()){

	$a = AudiowallSets::get_by_id(pg_escape_string($_REQUEST['setid']));

	if($a->user_can_delete() || Session::is_group_user('Audiowalls Admin')) {

		$user = Users::get_by_username(pg_escape_string($_REQUEST['username']));
		$owner = $a->get_owner();
		if ($owner != 0) {
			$ownerid = $owner->get_id();
		} else $ownerid = 0;

		if (!is_null($user)){
			$query = "permissions FROM aw_sets_permissions WHERE set_id = :set_id AND user_id = :user_id";
			$data = array('set_id' => $a->get_id(), 'user_id' => $user->get_id());
			$current = DigiplayDB::select($query, $data);
			if (!is_null($current)){
				if ($_REQUEST['val'] == 'editor') {
					if ($current[2] == '1') {
						$new = '111';
					} else {
						$new = '100';
					}
				} elseif ($_REQUEST['val'] == 'viewer') {
					if ($current[2] == '1') {
						$new = '111';
					} else {
						if ($current[1] == '1') {
							$new = '110';
						} else {
							$new = '000';
						}
					}
				} elseif ($_REQUEST['val'] == 'admin' && $user->get_id() != $ownerid) {
					$new = '110';
				}
				$data = array('permissions' => $new );
				DigiplayDB::update("aw_sets_permissions", $data, "set_id = '".$a->get_id()."' AND user_id = '".$user->get_id()."'");
			}
		} else {
			exit(json_encode(array("error" => "User Not Found!")));
		}

		if(Errors::occured()) { 

			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();

		} else {

			exit(json_encode(array('response' => 'success', 'id' => $a->get_id())));

		}

	} else {

		http_response_code(403);
		exit(json_encode(array('error' => 'Permission denied.')));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>
