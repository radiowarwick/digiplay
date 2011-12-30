<?php
require_once('pre.php');
Output::set_title("Music Library");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");

echo("<script>
	$(function () {
		$('.track-info').click(function(event) {
			event.preventDefault();
			var row = $(this).parent().parent();
			if(row.next().hasClass('detail')) {
				row.next().slideToggle();
			} else {
    			var id = row.attr('id');
    			$.ajax({
	         		type: 'GET',
         			url: '".SITE_LINK_REL."music/detail/index.php?id='+id+'&ajax=1',
         			dataType: 'html',
	         		success: function(data) {
	    	         	row.after('<tr class=\"detail\"><td colspan=\"7\">'+data+'</td></tr>');
         			}
    			});
    		}
		});
	});
</script>");

MainTemplate::set_subtitle("Add and remove tracks, edit track details");
$tracks = Tracks::get_newest();

function track_length($time_arr) {
	$time_str = ($time_arr["days"])? $time_arr["days"]."d " : "";
	$time_str .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
	$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "0m ";
	$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]."s " : "0s ";
	return $time_str;
}
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
			".((Session::is_admin() || Session::is_group_user("music_admin"))? "<th class=\"icon\"></th>" : "")."
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
			<td><a href=\"".SITE_LINK_REL."music/detail/".$track->get_id()."\" class=\"track-info\"><img src=\"".SITE_LINK_REL."images/icons/information.png\"></a></td>
			<td>".$artist_str."</td>
			<td>".$track->get_title()."</td>
			<td>".$import_date."</td>
			<td>".track_length(Time::seconds_to_dhms($track->get_length()))."</td>
			<td><a href=\"preview/".$track->get_id()."\" class=\"track-preview\"><img src=\"".SITE_LINK_REL."images/icons/sound.png\"></td>
			".((Session::is_admin() || Session::is_group_user("music_admin"))? "<td><a href=\"delete/".$track->get_id()."\" class=\"track-delete\"><img src=\"".SITE_LINK_REL."images/icons/delete.png\"></td>" : "")."
		</tr>");
	}
	echo("</table>");
} else {
	echo("Sorry, no results");
}
?>