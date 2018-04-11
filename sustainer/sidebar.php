<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Sustainer Control Centre", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/schedule.php", "text" => "Sustainer Schedule", "icon" => "music"),
		array("url" => LINK_ABS.$site_path_array[0]."/adverts.php", "text" => "Advert Manager", "icon" => "gbp")
	);

	if(Session::is_group_user("Administrators"))
		$menu[] = array("url" => LINK_ABS.$site_path_array[0]."/services.php", "text" => "Service Manager", "icon" => "cog");

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

		if($playlist->count_tracks() < 40) {
			$error = "Less than 40 tracks on playlist";
		}
		else {
			$length = 0;
			foreach($playlist->get_tracks() as $track) {
				$length += $track->get_length();
			}

			if($length < (2 * 60 * 60))
				$error = "Length of playlist less than 2 hours";
		}

		$icon = "";
		$hoverInformation = "";
		if(isset($error)) {
			$hoverInformation = " title=\"".$error."\" rel=\"twipsy\"";
			$icon = Bootstrap::fontawesome("exclamation-triangle", "fa-lg fa-fw fa-pull-left");
		}

		$return .= "<a href=\"../playlists/detail/".$playlist->get_id()."\" class=\"list-group-item\" style=\"background-color:".$color.";color:".$text."\"".$hoverInformation.">".$icon.$playlist->get_name()."</a>";
	}

	$return .= "</div>";

	return $return;
}
?>
