<?php

Output::set_title("Administration");
Output::add_script(LINK_ABS."js/jquery-ui-1.8.17.custom.min.js");
Output::add_script(LINK_ABS."js/bootstrap-popover.js");

MainTemplate::set_subtitle("View and edit configuration settings");

echo("<h3>Current settings:</h3>");

$locations = Locations::get_all();

echo "	<div class='accordion' id='accordion2'>";

foreach($locations as $location){
	echo "<div class='accordion-group'>
		<div class='accordion-heading'>
			<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion2' href='#collapse-".$location->get_id()."'>
				Location ".$location->get_id()."
			</a>
		</div>
		<div id='collapse-".$location->get_id()."' class='accordion-body collapse'>
			<div class='accordion-inner'>
												<table class=\"table table-striped\">
									<thead>
										<tr>
											<th class=\"icon\"></th>
											<th class=\"title\">Setting</th>
											<th class=\"icon\">Value</th>
											<th class=\"icon\"></th>
										</tr>
									</thead>
									<tbody>";
						$settings = Configs::get_by_location($location);
						$count = 1;
						foreach($settings as $setting){
									echo "<tr>
											<td>
												".$count."
											</td>
											<td>
												".$setting->get_parameter()."
											</td>
											<td>
												<strong>".$setting->get_val()."</strong> <br />
											</td>
											<td class=\"icon\">
												<span class='glyphicon glyphicon-edit'></span>
											</td>
										</tr>";
										$count = $count + 1;
						}
							echo "		</tbody>
									</table>
			</div>
		</div>
	</div>";
} echo "</div>";
?>