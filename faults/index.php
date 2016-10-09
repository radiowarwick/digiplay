<?php
Output::set_title("Fault Reporting");
Output::add_stylesheet(LINK_ABS."faults/style.css");
Output::add_stylesheet(LINK_ABS."faults/status.css");
MainTemplate::set_subtitle("View updates and report faults");
echo Bootstrap::alert_message_basic("warning","Most systems are operational","System Status:", false);


echo("<ul class=\"serviceList\">");
$statuses = Statuses::get(NULL);
foreach($statuses as $status){
	$statusInfo = $status->get_status_info();
	echo(
	"<li class=\"serviceList__item\">
      <p class=\"serviceList__status\"><span class=\"serviceStatusTag\" style=\"color:#".$statusInfo['colour']."\">".$statusInfo['status']."</span></p>
      <p class=\"serviceList__name\">
        ".$status->get_name()."
        <span class=\"serviceList__description has-tooltip\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"".$status->get_description()."\"></span>
      </p>
    </li>"
    );
}
echo("</ul>");

?>