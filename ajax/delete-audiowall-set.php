<?php

if(Session::is_user()){

	$aw_set = AudiowallSets::get_by_id($_REQUEST['setid']);

	if(!$aw_set) {
		http_response_code(400);
		exit(json_encode(array("error" => "Invalid audiowall set", "detail" => "Audiowall set does not exist")));
		Errors::clear();
	}

	if($aw_set->user_can_delete()) {

		// Remove audiowall owner and all users that have permissions
		DigiplayDB::delete("aw_sets_owner", "set_id = ".$aw_set->get_id());
		DigiplayDB::delete("aw_sets_permissions", "set_id = ".$aw_set->get_id());

		$users = $aw_set->get_users();

		DigiplayDB::delete("aw_sets", "id = ".$aw_set->get_id());
		
		foreach($users as $user){
			$data = array(
				'userid' => $user['userid'],
				'configid' => 1,
				'val' => '');
			DigiplayDB::insert("usersconfigs", $data);
		}
		
		if(Errors::occured()) { 

			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();

		} else {

			exit(json_encode(array('response' => 'success', 'id' => 1)));

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
