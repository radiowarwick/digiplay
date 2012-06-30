<?php
require_once('pre.php');
Output::set_title("Library Search");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
Output::add_script(SITE_LINK_REL."js/bootstrap-popover.js");

$query = $_REQUEST['q'];
$index = (isset($_REQUEST['i'])? $_REQUEST["i"] : "title artist album");
$limit = (isset($_GET['n']))? $_GET['n'] : 10;
$page = ($_REQUEST['p']? $_REQUEST['p'] : 1);

MainTemplate::set_subtitle("Find a track in the database, edit track details");
if($query) $search = Search::tracks($query,$index,$limit,(($page-1)*$limit));
$tracks = $search["results"];

if($tracks) {
	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->querystring = $query;
	$pages->index = $index;
	$pages->mid_range = 5;
	$pages->items_total = $search["total"];
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > $search["total"])? $search["total"] : $low + $limit - 1;

	echo("<script>
		$(function () {
			$('.track-info').popover({
				'html': true, 
				'title': function() { 
					return($(this).parent().parent().find('.title').html())
				},
				'content': function() {
					return($(this).parent().find('.hover-info').html());
				}
			});
		});
	</script>");

	$indexes = implode(", ", explode(" ", $index));
	echo("<h2>".$search["total"]." results for ".$query."<small> searching in ".$indexes."</small></h2>");
	echo("<div class=\"row\"><div class=\"span5\"><h4>Showing results ".$low." to ".$high."</h4></div><div class=\"pull-right\">".$pages->display_jump_menu().$pages->display_items_per_page()."</div></div>");
	echo("<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Album</th>
			<th class=\"length\">Length</th> 
			<th class=\"icon\"></th>
			".((Session::is_admin() || Session::is_group_user("Music Admin"))? "<th class=\"icon\"></th>" : "")."
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
			<td class=\"icon\">
				<a href=\"".SITE_LINK_REL."music/detail/".$track->get_id()."\" class=\"track-info\">
					<i class=\"icon-info-sign\"></i>
				</a>
				<div class=\"hover-info\">
					<strong>Artist:</strong> ".$artist_str."<br />
					<strong>Album:</strong> ".$track->get_album()->get_name()."<br />
					<strong>Year:</strong> ".$track->get_year()."<br />
					<strong>Length:</strong> ".Time::format_succinct($track->get_length())."<br />
					<strong>Origin:</strong> ".$track->get_origin()."<br />
					".($track->get_reclibid()? "<strong>Reclib ID:</strong> ".$track->get_reclibid()."<br />" : "")."
					<strong>On Sue:</strong> ".($track->is_sustainer()? "Yes" : "No")."<br />
					<strong>Censored:</strong> ".($track->is_censored()? "Yes" : "No")."<br /> 
				</div>
			</td>
			<td class=\"artist\">".$artist_str."</td>
			<td class=\"title\">".$track->get_title()."</td>
			<td class=\"album\">".$album."</td>
			<td class=\"length\">".Time::format_succinct($track->get_length())."</td>
			<td class=\"icon\"><a href=\"preview/".$track->get_id()."\" class=\"track-preview\" title=\"Preview this track\" rel=\"twipsy\"><i class=\"icon-volume-up\"></i></td>
			".((Session::is_admin() || Session::is_group_user("Music Admin"))? "<td class=\"icon\"><a href=\"delete/".$track->get_id()."\" class=\"track-delete\" title=\"Delete this track\" rel=\"twipsy\"><i class=\"icon-remove-sign\"></td>" : "")."
		</tr>");
	}
	echo("</table>");
	echo($pages->return);
	
} else {
	if($query) {
		echo("<h2>Sorry, no results for ".$query."</h2>");
		echo("<h3>Try a more generic search term.</h3>");
	} 
	echo("<h3>Enter keywords below to search for tracks:</h3>
	<form action=\"".SITE_LINK_REL."music/search\" method=\"GET\" class=\"form-inline\">
		<input type=\"text\" placeholder=\"Search Tracks\" name=\"q\">
       	<input type=\"submit\" class=\"btn primary\" value=\"Search\">
    </form>");
}
?>