<?php

Output::set_title("Sustainer");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_script(LINK_ABS."js/sustainer_schedule.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Perform common sustainer tasks");

echo("<script type=\"text/javascript\">
$(function() {

		$('#save-schedule').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/update-sustainer-slots.php',
				data: $('.field-slots').serialize(),
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

});
</script>");

$colours = array('2ecc71', 'e67e22', '3498db', 'e74c3c', '9b59b6', '34495e', '1abc9c', 'f1c40f');
$timeslots = array('00', '01', '02','03','04','05','06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');

$slots = SustainerSlots::get_all();
$i = 0;

echo("<h3>Sustainer schedule:</h3>");

echo("<select id=\"genre-selector\">");
foreach (Playlists::get_sustainer() as $playlist) {
	$i++;
	echo("<option value=\"".$playlist->get_id()."\" data-colour=\"".($playlist->get_colour() == "" ? 'FFFFFF' : $playlist->get_colour())."\">".$playlist->get_name()."</option>");
}
echo("</select>");

echo("<table class=\"table table-striped table-bordered\">
	<thead>
	<tr>
	<th></th>
	<th>Monday</th>
	<th>Tuesday</th>
	<th>Wednesday</th>
	<th>Thursday</th>
	<th>Friday</th>
	<th>Saturday</th>
	<th>Sunday</th>
	</tr>
	</thead>
	<tbody>");
$i = 0;
foreach ($slots as $slot) {
	if ($i < 1) {
		echo("<tr>
			<td>".$slot->get_time().":00</td>");
	}
	$thisPlaylist = Playlists::get_by_id($slot->get_playlist_id());
	$thisPlaylistColour = ($thisPlaylist->get_colour() == "" ? 'FFFFFF' : $thisPlaylist->get_colour());
	echo("<td class='timeslot' id='slot-".$slot->get_day()."-".$slot->get_time()."' style='background-color: #".$thisPlaylistColour.";'></td>");
	$i++;
	if ($i > 6) {
		echo("</tr>");
		$i = 0;
	}
}
echo("</tbody>
	</table>");

echo("<form>");

foreach ($slots as $slot) {
	echo("<input type=\"hidden\" class=\"field-slots\" id=\"field-slot-".$slot->get_day()."-".$slot->get_time()."\" name=\"field-slot-".$slot->get_day()."-".$slot->get_time()."\" value=\"".$slot->get_playlist_id()."\">");
}

echo("<button type=\"submit\" id=\"save-schedule\" class=\"btn btn-primary btn-block\">
		".Bootstrap::glyphicon("save save")."
		Save
	</button>");

echo("</form>");

?>