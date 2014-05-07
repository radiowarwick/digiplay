<?php
Output::set_title("Fault Reporting");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("info","1.0.4","Current Version:", false);
echo(
	"<br>
	<h3>Latest Updates</h3>
	<p>added audio walls0</p>

	".Bootstrap::panel("info", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque scelerisque, leo id aliquam pretium, est nisi fringilla turpis, vitae ultrices leo lectus vitae dui. Ut ultrices purus vitae lectus tempor, vel luctus purus dignissim. Etiam mattis lectus ut nisi euismod rutrum. Vivamus eu fringilla velit. Aenean vitae massa tincidunt, luctus erat sed, tincidunt elit. Integer non viverra nunc. Fusce vitae pharetra nisl. Fusce ac eleifend tortor.", "Update #1")
	); 
?>