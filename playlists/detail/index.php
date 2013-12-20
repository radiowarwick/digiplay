<?php

Output::set_title("Playlist Detail");

$playlist = Playlists::get_by_id($_REQUEST['q']);
$limit = (isset($_GET['n']))? $_REQUEST['n'] : 10;
$page = (isset($_REQUEST['p'])? $_REQUEST['p'] : 1);

MainTemplate::set_subtitle("List tracks on a playlist, remove tracks");

$tracks = $playlist->get_tracks($limit, (($page-1)*$limit));
if($tracks) {
	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->querystring = $playlist->get_id();
	$pages->mid_range = 5;
	$pages->items_total = $playlist->count_tracks();
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > $pages->items_total)? $pages->items_total : $low + $limit - 1;

	echo("<script>
		$(function () {
			$('.track-info').popover({
				'html': true, 
				'trigger': 'hover',
				'title': function() { 
					return($(this).parent().parent().find('.title').html())
				},
				'content': function() {
					return($(this).parent().find('.hover-info').html());
				}
			});
".(Session::is_group_user("Playlist Editor") ? "
			$('.track-remove').click(function() {
				trackid = $(this).attr('data-dps-track-id');
				playlistid = $(this).attr('data-dps-playlist-id');
				$.ajax({
					url: '".LINK_ABS."ajax/track-playlist-update.php',
					data: 'playlistid='+playlistid+'&trackid='+trackid+'&action=del',
					type: 'POST',
					error: function(xhr,text,error) {
						value = $.parseJSON(xhr.responseText);
						alert(value.error);
					},
					success: function(data,text,xhr) {
						window.location.reload(true); 
					}
				});
			});
" : "").
"		});
	</script>");

	echo("<h3>Tracks on playlist '".$playlist->get_name()."'</h3>");
	echo("<div class=\"row\"><div class=\"col-lg-5\"><h5>Showing results ".$low." to ".$high."</h5></div><div class=\"pull-right\">".$pages->display_jump_menu().$pages->display_items_per_page()."</div></div>");
	echo("<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Album</th>
			<th class=\"length nowrap\">Length</th> 
			".(Session::is_group_user("Playlist Editor")? "<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($tracks as $track) {
		echo("
		<tr id=\"".$track->get_id()."\">
			<td class=\"icon\">
				<a href=\"".LINK_ABS."music/detail/".$track->get_id()."\" class=\"track-info\">
					".Bootstrap::glyphicon("info-sign")."
				</a>
				<div class=\"hover-info\">
					<strong>Artist:</strong> ".$track->get_artists_str()."<br />
					<strong>Album:</strong> ".$track->get_album()->get_name()."<br />
					<strong>Year:</strong> ".$track->get_year()."<br />
					<strong>Length:</strong> ".Time::format_succinct($track->get_length())."<br />
					<strong>Origin:</strong> ".$track->get_origin()."<br />
					".($track->get_reclibid()? "<strong>Reclib ID:</strong> ".$track->get_reclibid()."<br />" : "")."
					<strong>Censored:</strong> ".($track->is_censored()? "Yes" : "No")."<br /> 
				</div>
			</td>
			<td class=\"artist\">".$track->get_artists_str()."</td>
			<td class=\"title\">".$track->get_title()."</td>
			<td class=\"album\">".$track->get_album()->get_name()."</td>
			<td class=\"length nowrap\">".Time::format_succinct($track->get_length())."</td>");
			echo((Session::is_group_user("Playlist Editor")? "<td class=\"icon\"><a href=\"#\" data-dps-track-id=\"".$track->get_id()."\" data-dps-playlist-id=\"".$playlist->get_id()."\" class=\"track-remove\" title=\"Remove this track\" rel=\"twipsy\">".Bootstrap::glyphicon("remove-sign")."</a></td>" : "")."
		</tr>");
	}
	echo("</table>");
	echo($pages->return);
	
} else {
	if($playlist) {
		echo("<h3>Sorry, no tracks are on the playlist '".$playlist->get_name()."'</h3>");
		if(Session::is_group_user("Playlist Editor")) echo("<h4>You can add tracks by finding them in the music library and clicking the ".Bootstrap::glyphicon("plus-sign").".</h4>");
	} else {
		echo("Invalid playlist.");
	}
}

?>