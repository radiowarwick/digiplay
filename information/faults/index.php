<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
$faults = Faults::get(NULL, 2);
foreach($faults as $fault){
	$title = "<b>Fault ID: DIGI_".$fault->get_id()."</b>";
	$body = "<p><i>Submitted by: <b>".$fault->get_author()."</b> on: <b>".$fault->get_postdate()."</b><span class=\"pull-right\">Status: <b>".$fault->get_real_status()."</b></span></i><hr></p>
	<p>".$fault->get_content()."</p>";
	echo( Bootstrap::panel("info", $body, $title) );
}

?>