<?php
require_once("pre.php");

if(Session::is_group_user('Playlist Admin')){
	foreach($_POST["id"] as $key => $id) {
		$playlist = Playlists::get_by_id($id);
		$playlist->set_sortorder(++$key);
		$playlist->save();
	}
	exit("success");
} else {
	exit("You do not have permission to modify this.");
}
?>