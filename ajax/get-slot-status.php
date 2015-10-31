<?php

if(Session::is_group_user('Sustainer Admin')){

	$slots = SustainerSlots::get_all();

	foreach ($slots as $slot) {

		$compareValue = "slot-".$slot->get_day()."-".$slot->get_time();

		if ($compareValue == $_REQUEST["updateid"]) {

			$prerecordText = "Currently this hour is scheduled with the <b>".Playlists::get_by_id($slot->get_playlist_id())->get_name()."</b> playlist";

			if ($slot->get_audio_id() != NULL) {
				$prerecordText .= " <i>AND</i> the prerecord <b>".Prerecs::get_by_id($slot->get_audio_id())->get_title()."</b> is scheduled.";
			} else {
				$prerecordText .=" <b>AND</b> there is no prerecord scheduled.";
			}
			break;
		}

	}

	if(Errors::occured()) { 

		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();

	} else {

		exit(json_encode(array('response' => 'success', 'status' => $prerecordText)));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>
