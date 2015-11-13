<?php
Output::set_title("Fault Reporting");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("warning","Most systems are operational","System Status:", false);
echo(
	"<h3>System Breakdown</h3>

	".Bootstrap::panel("danger", "<ul><li>log2</li><li>oap2</li></ul>", "Critical Systems")
	.Bootstrap::panel("warning", "<ul><li>log2</li><li>oap2</li></ul>", "Critical Systems")
	.Bootstrap::panel("success", "<ul><li>log2</li><li>oap2</li></ul>", "Critical Systems")
	); 
?>