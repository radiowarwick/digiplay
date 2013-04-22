<?php
function menu() {
	$menu = new Menu;
	$menu->add_many(
		array("index.php","Library Overview","home"),
		array("search", "Search Tracks", "search"),
		array("request","Request Tracks", "question-sign"),
		array("censor","Tag for Censorship", "exclamation-sign"),
		array("upload","Upload Tracks", "upload")
	);
	$site_path_array = explode("/",SITE_PAGE);

	$menu->set_active($site_path_array[1]);
	return $menu->output(SITE_LINK_REL."music/",6,"nav nav-list");
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
