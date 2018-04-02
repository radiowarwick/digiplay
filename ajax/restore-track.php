<?php

if(Session::is_group_user('Librarian')){
	if($_REQUEST["id"]) {
		$track = Tracks::get_by_id($_REQUEST["id"]);
		if($track->fetch_from_trash()) {
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