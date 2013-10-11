<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	if(Session::is_developer()) {

		$menu = array(
			array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Information", "icon" => "home"),
			array("url" => LINK_ABS.$site_path_array[0]."/faults", "text" => "My Fault Reports", "icon" => "list"),
			array("url" => LINK_ABS.$site_path_array[0]."/report", "text" => "Report a Fault", "icon" => "flash"),
			array("url" => LINK_ABS.$site_path_array[0]."/manage", "text" => "View Faults", "icon" => "inbox"),
		);

		$faults = Faults::get_open_faults();
		if($faults > 0) $menu[3]["badge"] = $faults;

	} else {

		$menu = array(
			array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Information", "icon" => "home"),
			array("url" => LINK_ABS.$site_path_array[0]."/faults", "text" => "My Fault Reports", "icon" => "list"),
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
    	"Replies" => $meminfo["Cached"]);

	$return .= "

	<h4>Fault Stats</h4>
	";
	foreach($memory as $key=>$val) {
		$return .= "<strong>".$key.": </strong>".$val."<br />";
	}

	return $return;
}
?>