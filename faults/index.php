<?php
Output::set_title("Fault Reporting");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("info","1.0.6 beta 3","Current Version:", false);
echo(
	"<br>
	<h3>Latest Updates</h3>

	".Bootstrap::panel("danger", "The fault system has broken, please do not try and report faults.", "Critical Error")
	); 
?>