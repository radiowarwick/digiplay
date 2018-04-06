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
					
					  <!-- Button trigger modal -->
					  <a data-toggle=\"modal\" href=\"#".$setting->get_parameter()."\">".Bootstrap::fontawesome("edit")."</a>

					  <!-- Modal -->
					  <div class=\"modal fade\" id=\"".$setting->get_parameter()."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
					    <div class=\"modal-dialog\">
					      <div class=\"modal-content\">
					        <div class=\"modal-header\">
					          <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
					          <h4 class=\"modal-title\">Change Value for ".$setting->get_parameter()."</h4>
					        </div>
					        <div class=\"modal-body\">
					          <form role=\"form\">
								  <div class=\"form-group\">
								    <label for=\"settingUpdateText\">Value for ".$setting->get_parameter()."</label>
								    <input type=\"text\" class=\"form-control\" id=\"settingUpdateText\" placeholder=\"Enter new value\" value=\"".$setting->get_val()."\">
								  </div>
					        </div>
					        <div class=\"modal-footer\">
					          <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
					          <button type=\"button\" class=\"btn btn-primary\">Save changes</button>
					        </div>
					        </form>
					      </div><!-- /.modal-content -->
					    </div><!-- /.modal-dialog -->
					  </div><!-- /.modal -->
				</td>
			</tr>");
}
echo("		</tbody>
	</table>");
?>