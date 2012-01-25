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
	return $return;
}
?>
