<?php

if(Session::is_group_user('Sustainer Admin')){

	$slots = SustainerSlots::get_all();

	foreach ($slots as $slot) {

		$compareValue = "slot-".$slot->get_day()."-".$slot->get_time();

		if ($compareValue == $_REQUEST["updateid"]) {

			if((int) $_REQUEST["prerecordid"] == 0) {
				$slot->set_audio_id((int) $_REQUEST["prerecordid"]);
				$slot->save();
				$returnID = 0;
				break;
			}

			if(!($audioid = Audio::get_by_id((int) $_REQUEST["prerecordid"]))) {
				exit(json_encode(array('error' => 'Invalid audio ID.')));
			} 
			
			if ((int) $_REQUEST["prerecordid"] != $slot->get_audio_id()) {
				$slot->set_audio_id((int) $_REQUEST["prerecordid"]);
				$slot->save();
			}

			$returnID = $audioid->get_id();
			
			break;
		}

	}

	if(Errors::occured()) { 

		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();

	} else {

		exit(json_encode(array('response' => 'success', 'id' => $returnID)));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>
