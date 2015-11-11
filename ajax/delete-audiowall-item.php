<?php

if(Session::is_user()){

	$id = (int) $_REQUEST['itemid'];

	$item = AudiowallItems::get_by_id($id);

	if(!$item) {
		http_response_code(400);
		exit(json_encode(array("error" => "Invalid audiowall item", "detail" => "Audiowall item does not exist")));
		Errors::clear();
	}

	$wall = Audiowalls::get_by_id($item->get_wall_id());
	$set = AudiowallSets::get_by_id($wall->get_set_id());

	if($set->user_can_edit()) {

		$query = "DELETE FROM \"aw_items\" WHERE \"id\" = ".$id;
		DigiplayDB::query($query);
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
