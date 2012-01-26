<?php
function sidebar() {
	$menu = new Menu;
	$menu->add_many(
		array("index.php","Library Overview"),
		array("search", "Search Tracks"),
		array("request","Request Tracks"),
		array("censor","Tag for Censorship"),
		array("upload","Upload Tracks")
	);
	$return = $menu->output(SITE_LINK_REL."music/",6);
	$return .= "
	<h3>Music Library</h3>
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
	<h3>Archive Storage</h3>
	<dl>";
	foreach(Archives::get_all() as $archive) {
		$return .= "
		<dt>".$archive->get_name()."</dt>
		<dd>".bytes($archive->get_free_space())." free of ".bytes($archive->get_total_space())."</dd>";
	}
	return $return;
}
?>
