<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Sustainer Control Centre", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/schedule.php", "text" => "Sustainer Schedule", "icon" => "music"),
		array("url" => LINK_ABS.$site_path_array[0]."/adverts.php", "text" => "Advert Manager", "icon" => "gbp")
	);

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {
	$return .= "
	<h4>Sustainer Service</h4>
	<dl>
		<dt>Tracks on Sustainer</dt>
		<dd>".Sustainer::get_total_tracks()."</dd>
		<dt>Length of Sustainer Playlist</dt>
		<dd>".Sustainer::get_total_length_formatted()."</dd>
	</dl>
	<h4>Sustainer Playlists</h4>
	<div class=\"list-group\">
	";

	foreach(Playlists::get_sustainer() as $playlist) {
		$color = $playlist->get_colour();
		if($color == "") {
			$color = "initial";
			$text = "#000";
		}
		else {
			$red = hexdec(substr($color, 0, 2));
			$green = hexdec(substr($color, 2, 2));
			$blue = hexdec(substr($color, 4, 2));

			if(($red*0.299 + $green*0.587 + $blue*0.114) > 186)
				$text = "#000";
			else
				$text = "#fff";
			$color = "#" . $color;
		}

		$return .= "<a href=\"../playlists/detail/".$playlist->get_id()."\" class=\"list-group-item\" style=\"background-color:".$color.";color:".$text."\">".$playlist->get_name()."</a>";
	}

	$return .= "</div>";

	return $return;
}
?>
