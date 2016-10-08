<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	if(Session::is_developer()) {

		$menu = array(
			array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "System Status", "icon" => "home"),
			array("url" => LINK_ABS.$site_path_array[0]."/fault.php", "text" => "My Fault Reports", "icon" => "list"),
			array("url" => LINK_ABS.$site_path_array[0]."/assigned.php", "text" => "My Assigned Faults", "icon" => "inbox"),
			array("url" => LINK_ABS.$site_path_array[0]."/report", "text" => "Report a Fault", "icon" => "flash"),
			array("url" => LINK_ABS.$site_path_array[0]."/manage", "text" => "View Faults", "icon" => "list-alt"),
		);

		$faults = Faults::get_open_faults();
		if($faults > 0) $menu[4]["badge"] = $faults;

		$faults = Faults::get_open_faults_user(Session::get_id());
		if($faults > 0) $menu[2]["badge"] = $faults;

	} else {

		$menu = array(
			array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "System Status", "icon" => "home"),
			array("url" => LINK_ABS.$site_path_array[0]."/fault.php", "text" => "My Fault Reports", "icon" => "list"),
			array("url" => LINK_ABS.$site_path_array[0]."/report", "text" => "Report a Fault", "icon" => "flash"),

		);

	}

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {
	
	$data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    foreach ($data as $line) {
    	list($key, $val) = explode(":", $line);
    	$meminfo[$key] = trim($val);
    }

    $memory = array(
    	"Total Faults" => Faults::get_total_faults(),
    	"Open Faults" => Faults::get_open_faults(),
    	"Closed Faults" => Faults::get_closed_faults(),
    	"Total Comments" => Comments::get_total_comments());

	$return .= "

	<h4>Fault Stats</h4>
	";
	foreach($memory as $key=>$val) {
		$return .= "<strong>".$key.": </strong>".$val."<br />";
	}

	return $return;
}
?>