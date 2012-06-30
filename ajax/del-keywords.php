<?php
require_once("pre.php");

if(Session::is_group_user('Music Admin')){
	if($_REQUEST["track_id"] && $_REQUEST["keyword"]) {
		$track = Tracks::get_by_id($_REQUEST["track_id"]);
		$track->del_keywords($_REQUEST["keyword"]);
	}
	exit("success");
} else {
	exit("You do not have permission to modify this.");
}
?>