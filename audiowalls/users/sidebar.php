<?php
function menu() {
	$site_path_array = explode("/",LINK_FILE);

	$menu = array(
		array("url" => LINK_ABS.$site_path_array[0]."/users/users-viewers.php?setid=".$_REQUEST['setid'], "text" => "Viewers", "icon" => "eye-open"),
		array("url" => LINK_ABS.$site_path_array[0]."/users/users-editors.php?setid=".$_REQUEST['setid'], "text" => "Editors", "icon" => "edit"),
		array("url" => LINK_ABS.$site_path_array[0]."/users/users-admins.php?setid=".$_REQUEST['setid'], "text" => "Admins", "icon" => "cog")
	);

	foreach($menu as &$item) if($site_path_array[2] == str_replace("?setid=".$_REQUEST['setid'], "", array_pop(explode("/",$item["url"])))) $item["active"] = true;
	return Bootstrap::list_group($menu);
}
function sidebar() {

}
?>
