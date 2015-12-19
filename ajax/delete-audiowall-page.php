<?php

if(Session::is_user()){

	$a = Audiowalls::get_by_id($_REQUEST['wallid']);

	$set = AudiowallSets::get_by_id($a->get_set_id());
	
	if ($set->user_can_edit()) {

		$a->delete();

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
