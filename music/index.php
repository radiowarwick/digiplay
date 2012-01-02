<?php
require_once('pre.php');
Output::set_title("Music Library");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
Output::add_script(SITE_LINK_REL."js/bootstrap-twipsy.js");
Output::add_script(SITE_LINK_REL."js/bootstrap-popover.js");

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

MainTemplate::set_subtitle("Add and remove tracks, edit track details");
$tracks = Tracks::get_newest();

if($tracks) {
	echo("<h2>10 newest tracks</h2>");
	echo("<table class=\"zebra-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"> </th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"album\">Date Added</th>
			<th class=\"length\">Length</th> 
			<th class=\"icon\"></th>
			".(Session::is_group_user("music_admin")? "<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($tracks as $track) {
		$artists = Artists::get_by_audio_id($track->get_id());
		$artist_str = "";
		foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
		$artist_str = substr($artist_str,0,-2);
		$import_date = date("d/m/Y H:i",$track->get_import_date());
		echo("
		<tr id=\"".$track->get_id()."\">
			<td class=\"icon\">
				<a href=\"".SITE_LINK_REL."music/detail/".$track->get_id()."\" class=\"track-info\">
					<img src=\"".SITE_LINK_REL."images/icons/information.png\">
				</a>
				<div class=\"hover-info\">
					<strong>Artist:</strong> ".$artist_str."<br />
					<strong>Album:</strong> ".$track->get_album()->get_name()."<br />
					<strong>Year:</strong> ".$track->get_year()."<br />
					<strong>Length:</strong> ".$track->get_length_formatted()."<br />
					<strong>Origin:</strong> ".$track->get_origin()."<br />
					".($track->get_reclibid()? "<strong>Reclib ID:</strong> ".$track->get_reclibid()."<br />" : "")."
					<strong>On Sue:</strong> ".($track->is_sustainer()? "Yes" : "No")."<br />
					<strong>Censored:</strong> ".($track->is_censored()? "Yes" : "No")."<br /> 
				</div>
			</td>
			<td class=\"artist\">".$artist_str."</td>
			<td class=\"title\">".$track->get_title()."</td>
			<td class=\"album\">".$import_date."</td>
			<td class=\"length\">".$track->get_length_formatted()."</td>
			<td class=\"icon\"><a href=\"preview/".$track->get_id()."\" class=\"track-preview\"><img src=\"".SITE_LINK_REL."images/icons/sound.png\"></td>
			".(Session::is_group_user("music_admin")? "<td class=\"icon\"><a href=\"delete/".$track->get_id()."\" class=\"track-delete\"><img src=\"".SITE_LINK_REL."images/icons/delete.png\"></td>" : "")."
		</tr>");
	}
	echo("</table>");
} else {
	echo("Sorry, no results");
}
?>