<?php


if(Session::is_group_user('Playlist Editor')){
	$track = Tracks::get($_REQUEST['trackid']);
	$playlist = Playlists::get($_REQUEST['playlistid']);
	$result = false;
	switch($_REQUEST['action']) {
	case "add":
		$result = $playlist->add_track($track);
		break;
	case "del":
		$result = $playlist->del_track($track);
		break;	
	}
	if($result) {
		$new_playlists = $track->get_playlists_in();
		$playlists_arr = array();
		foreach($new_playlists as $playlist) $playlists_arr[] = $playlist->get_id();
		exit(json_encode(array('result' => 'success', 'playlists' => $playlists_arr)));
	} else {
		http_response_code(400);
		exit(json_encode(array('error' => 'Unable to add track to playlist.')));
	}
} else {
	http_response_code(403);
}
?>