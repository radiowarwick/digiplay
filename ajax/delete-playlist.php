<?php
if(Session::is_group_user('Playlist Admin')){
	if($_REQUEST["id"]) {
		$playlist = Playlists::get_by_id($_REQUEST["id"]);
		$playlist->delete();
		if(Errors::occured()) { 
			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();
		} else {
			exit(json_encode(array('response' => 'success')));
		}
	}
} else {
	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));
}
?>