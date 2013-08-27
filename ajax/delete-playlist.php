<?php
if(Session::is_group_user('Playlist Admin')){
	if($_REQUEST["id"]) {
		$playlist = Playlists::get_by_id($_REQUEST["id"]);
		if($playlist->delete()) {
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