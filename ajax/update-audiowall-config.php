<?php

if(Session::is_user()){

	$set = AudiowallSets::get($_REQUEST['awid']);

	if ($set->user_can_view())) {

		$data = array(
			"val" => (int) $_REQUEST['awid']
		);

		$thisUserID = (int) $_REQUEST['userid'];
		
		DigiplayDB::update("usersconfigs", $data, "userid = ".$thisUserID." AND configid = 1");

		if(Errors::occured()) { 

			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();

		} else {

			exit(json_encode(array('response' => 'success', 'id' => $data['val'])));

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
