<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("info","1.0.4","Current Version:", false);
$faults = Faults::get(NULL);
foreach($faults as $fault){
	$title = "<b>Fault ID: DIGI_".$fault->get_id()." </b><small>Assigned to: ".$fault->get_real_assignedto($fault->get_assignedto())."</small><span class=\"pull-right label label-".$fault->get_panel_class()."\">".$fault->get_real_status()."</span>";
	$footer = "<a href=\"#\" class=\"btn btn-primary btn-xs\">Add Comment</a> 
	<a data-toggle=\"modal\" href=\"#".$fault->get_id()."-status\" class=\"btn btn-success btn-xs\">Change Status</a> 
	<a data-toggle=\"modal\" href=\"#".$fault->get_id()."-assign\" class=\"btn btn-warning btn-xs\">Assign Fault</a> 
	<a data-toggle=\"modal\" href=\"#".$fault->get_id()."-delete\" class=\"btn btn-danger btn-xs\">Delete</a>
	<span class=\"pull-right\">".Bootstrap::glyphicon("plus")."</span> ";
	$body = "<p><i>Submitted by: <b>".$fault->get_real_author($fault->get_author())."</b> on: <b>".$fault->get_postdate()."</b></i><hr></p>
	<p>".$fault->get_content()."</p>";
	echo( Bootstrap::panel($fault->get_panel_class(), $body, $title, $footer) );
	$title = "Change the status of fault DIGI_".$fault->get_id();
	$body = "<form role=\"form\" method=\"post\" action=\"../../ajax/fault-admin.php?action=update-status&id=".$fault->get_id()."\">
	  <div class=\"form-group\">
	    <select class=\"form-control\" name=\"status\">
		  <option value=\"1\">Not yet read</option>
		  <option value=\"2\">On hold</option>
		  <option value=\"3\">Work in progress</option>
		  <option value=\"4\">Fault complete</option>
		</select>
	  </div>
	  <div class=\"form-group\">
	  <button type=\"submit\" class=\"btn btn-success\">Change Status</button>
	  <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>
	  </div>
	</form>";
	echo( Bootstrap::modal($fault->get_id()."-status", $body, $title) );
	$title = "Change the status of fault DIGI_".$fault->get_id();
	$body = "<form role=\"form\" method=\"post\" action=\"../../ajax/fault-admin.php?action=assign-fault&id=".$fault->get_id()."\">
	  <div class=\"form-group\">
	    <select class=\"form-control\" name=\"assign\">";
	$group = Groups::get_by_name("Developers");
	$developers = $group->get_users();
	foreach($developers as $developer) {
		$user = Users::get_by_id($developer->get_id());
		$user_information = $user->get_ldap_attributes();
		$user_fullname = $user_information['first_name']." ".$user_information['surname'];
		$body .= "<option value=".$developer->get_id().">".$user_fullname."</option>";
	}
	$body .= "</select>
	  </div>
	  <div class=\"form-group\">
	  <button type=\"submit\" class=\"btn btn-warning\">Assign Fault</button>
	  <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>
	  </div>
	</form>";
	echo( Bootstrap::modal($fault->get_id()."-assign", $body, $title) );
	$title = "Are you sure you want to delete fault DIGI_".$fault->get_id();
	$body = "<a href=\"../../ajax/fault-admin.php?action=del-fault&id=".$fault->get_id()."\" class=\"btn btn-danger\">Delete</a> <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>";
	echo( Bootstrap::modal($fault->get_id()."-delete", $body, $title) );
}
?>