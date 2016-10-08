<?php
Output::set_title("Fault Reporting");
Output::add_stylesheet(LINK_ABS."faults/style.css");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("warning","Most systems are operational","System Status:", false);
echo(
	"<h3>System Breakdown</h3>

	".Bootstrap::panel("danger", "<ul><li>log2</li><li>oap2</li></ul>", "Systems Status")
	); 

$statuses = Statuses::get(NULL);
foreach($statuses as $status){
	echo($status->get_name()." <div class=\"status-circle ".$status->get_real_status()."\"</div>");
}
?>