<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/users-editors.php?setid=".$_REQUEST['setid'], "text" => "Editors", "icon" => "home"),
		array("url" => LINK_ABS.$site_path_array[0]."/users-viewers.php?setid=".$_REQUEST['setid'], "text" => "Viewers", "icon" => "folder-open")
	);

	foreach($menu as &$item) if($site_path_array[1] == array_pop(explode("/",$item["url"]))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {

}
?>
