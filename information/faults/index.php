<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
$faults = Faults::get(NULL, Session::get_id());
foreach($faults as $fault){
	$title = "<b>Fault ID: DIGI_".$fault->get_id()."</b><span class=\"pull-right label label-".$fault->get_panel_class()."\">".$fault->get_real_status()."</span>";
		$footer = "<a href=\"#\" class=\"btn btn-primary btn-xs\">Add Comment</a><span class=\"pull-right\">".Bootstrap::glyphicon("plus")."</span>";
	$body = "<p><i>Submitted by: <b>".$fault->get_author()."</b> on: <b>".$fault->get_postdate()."</b></i><hr></p>
	<p>".$fault->get_content()."</p>";
	echo( Bootstrap::panel($fault->get_panel_class(), $body, $title, $footer) );
}

?>