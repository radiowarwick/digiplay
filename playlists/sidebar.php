<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Playlists Overview", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/index.php#add", "text" => "Add a new playlist", "icon" => "plus-sign"),
		array("url" => LINK_ABS.$site_path_array[0]."/detail/0", "text" => "Edit sustainer playlist", "icon" => "edit")
	);

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {


	$tracks = Tracks::get_playlisted();
	foreach ($tracks as $track) { $total_length += $track->get_length(); }

	$time_arr = Time::seconds_to_dhms($total_length);
	$total_length = ($time_arr["days"])? $time_arr["days"]."d " : "";
	$total_length .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
	$total_length .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "0m ";
	$total_length .= ($time_arr["seconds"])? sprintf('%02d',$time_arr["seconds"])."s " : "00s ";

	$return .= "
	<h4>Playlists</h4>
	<dl>
		<dt>Playlisted Tracks:</dt>
		<dd>".count($tracks)."</dd>
		<dt>Length of Playlists:</dt>
		<dd>".$total_length."</dd>
	</dl>
	<h4>Sustainer Service</h4>
	<dl>
		<dt>Tracks on Sustainer</dt>
		<dd>".Sustainer::get_total_tracks()."</dd>
		<dt>Length of Sustainer Playlist</dt>
		<dd>".Sustainer::get_total_length_formatted()."</dd>
	</dl>";

	return $return;
}
?>