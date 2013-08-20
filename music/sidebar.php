<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Library Overview", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/search", "text" => "Search Tracks", "icon" => "search"),
		array("url" => LINK_ABS.$site_path_array[0]."/request", "text" => "Request Tracks", "icon" => "question-sign"),
		array("url" => LINK_ABS.$site_path_array[0]."/censor", "text" => "Tag for Censorship", "icon" => "exclamation-sign"),
		array("url" => LINK_ABS.$site_path_array[0]."/upload", "text" => "Upload Tracks", "icon" => "upload")
	);

	$requests = Requests::count();
	if($requests > 0) $menu[2]["badge"] = $requests;

	$flagged = count(Tracks::get_flagged());
	if($flagged > 0) $menu[3]["badge"] = $flagged;

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {
	$return .= "
	<h4>Music Library</h4>
	<dl>
		<dt>Tracks</dt>
		<dd>".number_format(Tracks::get_total_tracks())."</dd>
		<dt>Artists</dt>
		<dd>".number_format(Artists::count())."</dd>
		<dt>Albums</dt>
		<dd>".number_format(Albums::count())."</dd>
	</dl>";

	function bytes($a) {
    	$unim = array("B","KB","MB","GB","TB","PB");
    	$c = 0;
    	while ($a>=1024) {
        	$c++;
        	$a = $a/1024;
    	}
    	return number_format($a,($c ? 2 : 0),".",",")." ".$unim[$c];
	}

	$return .= "
	<h4>Archive Storage</h4>
	<dl>";
	foreach(Archives::get_all() as $archive) {
		$pc = (int) ( 100 - ( $archive->get_free_space()/$archive->get_total_space() * 100 ) );
		if ( $archive->get_free_space() > 536870912000 ) {
			$colour = "success";
		} else if ( $archive->get_free_space() > 214748364800 ) {
			$colour = "warning";
		} else {
			$colour = "danger";
		}
		$return .= "
		<dt>".$archive->get_name()."</dt>
		<dd><div class=\"progress\" style=\"margin: 3px 0px; \"><div class=\"progress-bar progress-bar-".$colour."\" style=\"width: ".$pc."%;\"></div></div></dd>
		<dd>".bytes($archive->get_free_space())." free of ".bytes($archive->get_total_space())."</dd>";
	}
	$return .= "</dl>";
	return $return;
}


?>
