<?php
require_once("pre.php");

if(Session::is_group_user('Music Admin')){
	if($_REQUEST["id"]) {
		$track = Tracks::get_by_id($_REQUEST["id"]);
		if($track->move_to_trash()) {
			Search::update_index();
			exit(json_encode(array('response' => 'success')));
		} else {
			http_response_code(403);
			exit(json_encode(array('error' => 'Unknown error.')));
		}
	}
} else {
	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));
}
?>