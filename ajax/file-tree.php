<?php

if(!Session::is_group_user("Administrators")) {
	http_response_code(401);
	exit(json_encode(array("error" => "You must be an adminitrator to do this.")));
}
if(!isset($_REQUEST["id"]) || !($file = Files::get_by_id($_REQUEST["id"]))) {
	http_response_code(400);
	exit(json_encode(array("error" => "Invalid directory specified, or no permission to read it.")));
}

$children = $file->get_children();

$return = array();

foreach($children as $child) {
	$array = array(
		"title" => $child->get_name(),
		"key" => $child->get_itemtype().$child->get_id(),
		"id" => $child->get_id(),
		"type" => $child->get_itemtype()
	);

	if($child->get_itemtype() == "dir") $array["folder"] = true; 
	if($child->has_children()) $array["lazy"] = true;

	$return[] = $array;
}

echo(json_encode($return));