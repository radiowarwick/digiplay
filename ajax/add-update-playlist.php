<?php
if(Session::is_group_user('Playlist Admin')){
	if(is_null($_REQUEST['id'])) {
		if(!is_null($_REQUEST['name'])) {
			$playlist = new Playlist();
			$playlist->set_name($_REQUEST['name']);
			if($playlist->save()) {
				exit(json_encode(array('response' => 'success', 'id' => $playlist->get_id())));
			} else {
				http_response_code(403);
				exit(json_encode(array('error' => 'Unknown error.')));
			}
		} else {
			exit(json_encode(array('error' => 'No name specified for playlist.')));
		}
	} else {
		if(!($playlist = Playlists::get_by_id($_REQUEST['id']))) exit(json_encode(array('error' => 'Invalid playlist ID.')));
		$playlist->set_name($_REQUEST['name']);
		if($playlist->save()) {
			exit(json_encode(array('response' => 'success', 'id' => $playlist->get_id())));
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