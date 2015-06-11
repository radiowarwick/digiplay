<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/index.php", "text" => "My Files", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/system", "text" => "System Files", "icon" => "folder-open"),
		array("url" => LINK_ABS.$site_path_array[0]."/packages", "text" => "Jingle Packages", "icon" => "book"),
	);

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {

}
?>
