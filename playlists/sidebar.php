<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Playlists Overview", "icon" => "home")
	);

	if(Session::is_group_user("Playlist Admin")) {
		$menu[] = array("url" => LINK_ABS.$site_path_array[0]."/index.php#add\" data-toggle=\"modal\" data-target=\"#addnew-modal", "text" => "Add a new playlist", "icon" => "plus-sign");
	}

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
	<h4>Playlists</h4>
	<dl>
		<dt>Playlisted Tracks:</dt>
		<dd>".$tracks."</dd>
		<dt>Length of Playlists:</dt>
		<dd>".Time::format_succinct($total_length)."</dd>
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
