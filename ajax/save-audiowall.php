<?php

if(Session::is_user()){

	$set = AudiowallSets::get($_REQUEST['id']);

	if ($set->user_can_edit()) {

		$set->set_name($_REQUEST['name']);
		$set->set_description($_REQUEST['description']);

		foreach ($_REQUEST['walls'] as $walldata) {
			$wall = new Audiowall;
			$wall->set_id($walldata[id]);
			$wall->set_name($walldata[name]);
			$wall->set_description($walldata[name]);
			$wall->set_page($walldata[page]);
			foreach($walldata[items] as $itemdata) {
				$item = new AudiowallItem();
				$item->set_audio_id($itemdata[audio_id]);
				$item->set_style_id($itemdata[style_id]);
				$item->set_item($itemdata[item]);
				$item->set_text(str_replace("<br>", "\n", $itemdata[text]));
				//print_r($item);
				$wall->add_item($item);
			}
			$set->add_wall($wall);
		}
		$set->save();

	} else {

		http_response_code(403);
		exit(json_encode(array('error' => 'Permission denied.')));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>