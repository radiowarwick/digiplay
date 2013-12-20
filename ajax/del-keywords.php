<?php
if(Session::is_group_user('Librarian')){
	if($_REQUEST["track_id"] && $_REQUEST["keyword"]) {
		$track = Tracks::get_by_id($_REQUEST["track_id"]);
		$track->del_keywords($_REQUEST["keyword"]);
	}

	if(Errors::occured()) { 
		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();
	} else {
		exit('success');
	}
} else {
	http_response_code(403);
	exit("You do not have permission to modify this.");
}
?>