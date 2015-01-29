<?php

Output::set_title("Sustainer");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_script(LINK_ABS."js/sustainer_schedule.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Perform common sustainer tasks");

$colours = array('2ecc71', 'e67e22', '3498db', 'e74c3c', '9b59b6', '34495e', '1abc9c', 'f1c40f');
$timeslots = array('00', '01', '02','03','04','05','06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
$days = array('m', 'tu', 'w', 'th', 'f', 'sa', 'su');

$currentQueue = Sustainer::get_queue();
$i = 0;

echo("<h3>Sustainer schedule:</h3>");

echo("<select id=\"genre-selector\">");
foreach (Playlists::get_sustainer() as $playlist) {
	$i++;
	echo("<option value=\"".$colours[$i]."\">".$playlist->get_name()." - ".$colours[$i]."</option>");
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
foreach ($timeslots as $timeslot) {
	echo("<tr>
	<td>".$timeslot.":00</td>");
	foreach ($days as $day) {
		echo("<td class='timeslot' id='slot-".$day."-".$timeslot."' style='background-color: #".$colours[rand(0, count($colours) - 1)].";'></td>");
	}
	echo("</tr>");
}
echo("</tbody>
	</table>");

echo("<form>");
foreach ($timeslots as $timeslot) {
	foreach ($days as $day) {
		echo("<input type=\"hidden\" id=\"field-slot-".$day."-".$timeslot."\" name=\"field-slot-".$day."-".$timeslot."\" value=\"2ecc71\">");
	}
}
echo("</form>");

?>