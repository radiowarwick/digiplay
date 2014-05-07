<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "Admin Overview", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/users", "text" => "User Administration", "icon" => "user"),
		array("url" => LINK_ABS.$site_path_array[0]."/groups", "text" => "Group Administration", "icon" => "indent-left"),
		array("url" => LINK_ABS.$site_path_array[0]."/locations", "text" => "Location Configuration", "icon" => "globe"),
		array("url" => LINK_ABS.$site_path_array[0]."/system", "text" => "System Configuration", "icon" => "cog"),
	);

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {
	
	$load = sys_getloadavg();
	$data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    foreach ($data as $line) {
    	list($key, $val) = explode(":", $line);
    	$meminfo[$key] = trim($val);
    }

    $memory = array(
    	"Total" => $meminfo["MemTotal"],
    	"Free" => $meminfo["MemFree"],
    	"Buffers" => $meminfo["Buffers"],
    	"Cached" => $meminfo["Cached"],
    	"Swap Total" => $meminfo["SwapTotal"],
    	"Swap Free" => $meminfo["SwapFree"]);

	$return .= "
	<h4>Server Load</h4>
	<strong>1 min: </strong>".$load[0]."<br />
	<strong>5 min: </strong>".$load[1]."<br />
	<strong>15 min: </strong>".$load[2]."<br />
	<hr />

	<h4>Memory Stats</h4>
	";
	foreach($memory as $key=>$val) {
		$return .= "<strong>".$key.": </strong>".$val."<br />";
	}

	return $return;
}
?>