<?php

Output::require_group("Administrators");

Output::set_title("Sustainer Services");
MainTemplate::set_subtitle("Manage the sustainer services");

if(isset($_POST["restart-marceline"])) {
    system("sudo /etc/init.d/marceline restart");
}

if(isset($_POST["restart-javo"])) {
    system("sudo /etc/init.d/javo restart");
}
?>

<div class="row">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th class="title">Service</th>
				<th class="title">Status</th>
				<th class="icon">Restart</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Marceline</td>
				<td>
          <?echo preg_replace('/\(pid\s\d+\)/', '', substr(exec("sudo /etc/init.d/marceline status"), 8));?>
        </td>
				<td><form method="POST"><input name="restart-marceline" type="submit" class="btn btn-danger" value="Restart" /></form></td>
			</tr>
      <tr>
        <td>JAVO</td>
        <td>
          <?echo preg_replace('/\(pid\s\d+\)/', '', substr(exec("sudo /etc/init.d/javo status"), 8));?>
        </td>
        <td><form method="POST"><input name="restart-javo" type="submit" class="btn btn-danger" value="Restart" /></form></td>
      </tr>
		</tbody>
	</table>
</div>