<?php
require_once('pre.php');
Output::set_title("Music Library");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");

$query = $_REQUEST['q'];
$limit = (isset($_GET['n']))? $_GET['n'] : 20;
$page = ($_REQUEST['p']? $_REQUEST['p'] : 1);

MainTemplate::set_subtitle("Add and remove tracks, edit track details");
$search = Search::tracks($query,$limit,(($page-1)*$limit));
$tracks = $search["results"];

$pages = new Paginator;
$pages->items_per_page = $limit;
$pages->querystring = $query;
$pages->mid_range = 5;
$pages->items_total = $search["total"];
$pages->paginate();

$low = (($page-1)*$limit+1);
$high = (($low + $limit - 1) > $search["total"])? $search["total"] : $low + $limit - 1;

echo("<h2>".$search["total"]." results for ".$query."</h2>");
echo("<div class=\"row\"><div class=\"span8\"><h4>Showing results ".$low." to ".$high."</h4></div><div class=\"span4\">".$pages->display_jump_menu().$pages->display_items_per_page()."</div></div>");
function track_length($time_arr) {
	$time_str = ($time_arr["days"])? $time_arr["days"]."d " : "";
	$time_str .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
	$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "0m ";
	$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]."s " : "0s ";
	return $time_str;
}

if($tracks) {
	echo("<table class=\"zebra-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Album</th>
			<th class=\"length\">Length</th> 
			<th class=\"icon\"></th>
			".((Session::is_admin() || Session::is_group_user("music_admin"))? "<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($tracks as $track_id) {
		$track = Tracks::get($track_id);
		$artists = Artists::get_by_audio_id($track->get_id());
		$artist_str = "";
		foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
		$artist_str = substr($artist_str,0,-2);
		$album = Albums::get_by_audio_id($track->get_id());
		$album = $album->get_name();
		echo("
		<tr>
			<td><a href=\"detail/".$track->get_id()."\" class=\"track-info\"><img src=\"".SITE_LINK_REL."images/icons/information.png\"></a></td>
			<td>".$artist_str."</td>
			<td>".$track->get_title()."</td>
			<td>".$album."</td>
			<td>".track_length(Time::seconds_to_dhms($track->get_length()))."</td>
			<td><a href=\"preview/".$track->get_id()."\" class=\"track-preview\"><img src=\"".SITE_LINK_REL."images/icons/sound.png\"></td>
			".((Session::is_admin() || Session::is_group_user("music_admin"))? "<td><a href=\"delete/".$track->get_id()."\" class=\"track-delete\"><img src=\"".SITE_LINK_REL."images/icons/delete.png\"></td>" : "")."
		</tr>");
	}
	echo("</table>");
	echo($pages->return);
	/*echo("
		<div class=\"pagination\">
			<ul>
				<li class=\"prev".(($page == 1)? " disabled\"><a href=\"#" : "\"><a href=\"?q=".$query."&p=".($page-1))."\">Previous</a></li>");
	echo("
				<li class=\"next".(($page == $pages)? " disabled\"><a href=\"#" : "\"><a href=\"?q=".$query."&p=".($page+1))."\">Next</a></li>
			</ul>
		</div>");*/
} else {
	echo("Sorry, no results");
}
?>