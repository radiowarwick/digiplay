<?php
require_once("pre.php");
Output::set_template();

if(Session::is_group_user('music_admin')){
	if($_REQUEST["track_id"] && $_REQUEST["keyword_id"]) {
		$track = Tracks::get_by_id($_REQUEST["track_id"]);
		$track->del_keywords($_REQUEST["keyword_id"]);
	}
	exit("success");
} else {
	exit("Error: You do not have permission to modify this.");
}
?>