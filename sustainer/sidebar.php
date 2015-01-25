<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Sustainer Overview", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/timer.php", "text" => "More coming soon...", "icon" => "edit")
	);

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {

	foreach(Playlists::get_all(false) as $playlist) {
		foreach ($playlist->get_tracks() as $track) {
			$tracks++;
			$total_length += $track->get_length();
		}
	}
	

	$return .= "
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
