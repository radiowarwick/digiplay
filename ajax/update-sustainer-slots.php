<?php

if(Session::is_group_user('Sustainer Admin')){

	$slots = SustainerSlots::get_all();

	foreach ($slots as $slot) {

		$slotIdentifier = "field-slot-".$slot->get_day()."-".$slot->get_time();

		if(!($playlist = Playlists::get_by_id($_REQUEST["$slotIdentifier"]))) exit(json_encode(array('error' => 'Invalid playlist ID.')));
		
		if ($_REQUEST["$slotIdentifier"] != $slot->get_playlist_id()) {
			$slot->set_playlist_id($_REQUEST["$slotIdentifier"]);
			$slot->save();
		}

	}

	if(Errors::occured()) { 

		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();

	} else {

		exit(json_encode(array('response' => 'success', 'id' => $playlist->get_id())));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>
