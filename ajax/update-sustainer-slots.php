<?php

if(Session::is_group_user('Sustainer Admin')){

	$slots = SustainerSlots::get_all();

	foreach ($slots as $slot) {

		$compareValue = "slot-".$slot->get_day()."-".$slot->get_time();

		if ($compareValue == $_REQUEST["updateid"]) {
			if(!($audioid = Audio::get_by_id((int) $_REQUEST["playlistid"]))) exit(json_encode(array('error' => 'Invalid playlist ID.')));
			
			if ((int) $_REQUEST["playlistid"] != $slot->get_playlist_id()) {
				$slot->set_playlist_id((int) $_REQUEST["playlistid"]);
				$slot->save();
			}
			break;
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
