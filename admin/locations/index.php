<?php

Output::set_title("Location Configuration");

MainTemplate::set_subtitle("View and edit configuration settings for each location");

$locations = Locations::get_all();

echo "<div class=\"panel-group\" id=\"accordion\">";
foreach($locations as $location){
	echo "
	<div class=\"panel panel-default\">
		<div class=\"panel-heading\" data-toggle=\"collapse\" href=\"#location-".$location->get_id()."\">
			<h4 class=\"panel-title\">
				Location ".$location->get_id()."
			</h4>
		</div>
		<div id=\"location-".$location->get_id()."\" class=\"panel-collapse collapse\">
			<table class=\"table table-striped table-hover\">
				<thead>
					<tr>
						<th class=\"title\">Setting</th>
						<th class=\"icon\">Value</th>
						<th class=\"icon\"></th>
					</tr>
				</thead>
				<tbody>";
	$settings = Configs::get_by_location($location);
	foreach($settings as $setting){
				echo "<tr>
						<td>
							".$setting->get_parameter()."
						</td>
						<td>
							<strong>".$setting->get_val()."</strong> <br />
						</td>
						<td class=\"icon\">
							".Bootstrap::fontawesome("edit")."
						</td>
					</tr>";
	}
	echo "		</tbody>
			</table>
		</div>
	</div>";
} 
echo "</div>";
?>