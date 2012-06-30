<?php
require_once("pre.php");

if(Session::is_group_user('Music Admin')){
	$track = Tracks::get_by_id($_REQUEST["id"]);
	if(!$_REQUEST["title"]) exit("You must specify a title");
	if($_REQUEST["title"] != $track->get_title()) $track->set_title($_REQUEST["title"]);

	$curr_artists_obj = $track->get_artists();
	$curr_artists_arr = array();
	foreach($curr_artists_obj as $artist) $curr_artists_arr[] = $artist->get_name();
	$track->del_artists(array_diff($curr_artists_arr,$_REQUEST["artist"]));
	$track->add_artists(array_diff($_REQUEST["artist"],$curr_artists_arr));
	
	if($_REQUEST["new_artist"]) $track->add_artists($_REQUEST["new_artist"]);

	if(!$_REQUEST["album"]) $_REQUEST["album"] = "(none)";
	if($_REQUEST["album"] != $track->get_album()->get_name()) $track->set_album($_REQUEST["album"]);

	if($_REQUEST["year"] != $track->get_year()) $track->set_year($_REQUEST["year"]);

	if(!$_REQUEST["origin"]) exit("You must specify an origin");
	if($_REQUEST["origin"] != $track->get_origin()) $track->set_origin($_REQUEST["origin"]);

	if($_REQUEST["reclibid"] != $track->get_reclibid()) $track->set_reclibid($_REQUEST["reclibid"]);

	$track->set_censored($_REQUEST["censored"]);
	$track->set_sustainer($_REQUEST["sustainer"]);

	$result = $track->save();
	if(!$result) exit("Something is incorrect.  You may have discovered a bug!");

	$result = $track->save_audio();
	if(!$result) exit("Something is incorrect.  You may have discovered a bug!");

	exit("success");
} else {
	exit("You do not have permission to modify this.");
}
?>