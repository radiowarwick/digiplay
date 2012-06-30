<?php
require_once("pre.php");

if(Session::is_group_user('Music Admin')){
	/* update notes */
	$track = Tracks::get_by_id($_REQUEST["id"]);
	if($_REQUEST["notes"] != $track->get_notes()) {
		$track->set_notes($_REQUEST["notes"]);
		$result = $track->save_audio();
		if(!$result) exit("Something is incorrect.  You may have discovered a bug!");
	}
	if($_REQUEST["new_keyword"]) $track->add_keywords($_REQUEST["new_keyword"]);
	exit("success");
} else {
	exit("You do not have permission to modify this.");
}
?>