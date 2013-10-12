<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("info","1.0.4","Current Version:", false);
$faults = Faults::get(NULL);
foreach($faults as $fault){
	$title = "<b>Fault ID: DIGI_".$fault->get_id()."</b><span class=\"pull-right label label-".$fault->get_panel_class()."\">".$fault->get_real_status()."</span>";
	$footer = "<a href=\"#\" class=\"btn btn-primary btn-xs\">Add Comment</a> 
	<a href=\"#\" class=\"btn btn-success btn-xs\">Change Status</a> 
	<a href=\"#\" class=\"btn btn-warning btn-xs\">Assign Fault</a> 
	<a data-toggle=\"modal\" href=\"#".$fault->get_id()."-delete\" class=\"btn btn-danger btn-xs\">Delete</a>
	<span class=\"pull-right\">".Bootstrap::glyphicon("plus")."</span> ";
	$body = "<p><i>Submitted by: <b>".$fault->get_real_author($fault->get_author())."</b> on: <b>".$fault->get_postdate()."</b></i><hr></p>
	<p>".$fault->get_content()."</p>";
	echo( Bootstrap::panel($fault->get_panel_class(), $body, $title, $footer) );
	$title = "Are you sure you want to delete fault DIGI_".$fault->get_id();
	$body = "<a href=\"../../ajax/delete-fault.php?id=".$fault->get_id()."\" class=\"btn btn-danger\">Delete</a> <a href=\"#\" data-dismiss=\"modal\" class=\"btn btn-default\">Cancel</a>";
	echo( Bootstrap::modal($fault->get_id()."-delete", $body, $title) );
}
?>