<?php
Output::set_title("System Configuration");
MainTemplate::set_subtitle("View and edit system configuration settings");

echo("
	<table class=\"table table-striped table-hover\">
		<thead>
			<tr>
				<th class=\"title\">Setting</th>
				<th class=\"icon\">Value</th>
				<th class=\"icon\"></th>
			</tr>
		</thead>
		<tbody>");
$settings = Configs::get(NULL,-1);
foreach($settings as $setting){
		echo("<tr>
				<td>
					".$setting->get_parameter()."
				</td>
				<td>
					<strong>".$setting->get_val()."</strong> <br />
				</td>
				<td class=\"icon\">
					<span class='glyphicon glyphicon-edit'></span>
				</td>
			</tr>");
}
echo("		</tbody>
	</table>");
?>