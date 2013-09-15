<?php
Output::set_template();
if(isset($_REQUEST["key"])) {
	$location = Locations::get_by_key($_REQUEST["key"]);
	$key = $_REQUEST["key"];
} else {
	if(isset($_REQUEST["location"])) {
		$location = Locations::get_by_id($_REQUEST["location"]);
		$key = $location->get_key();
	} else {
		exit("No location specified!");
	}
}

switch($_REQUEST["action"]) {
	case "check-next":
		$next = Configs::get(NULL,$location,"next_on_showplan")->get_val();
		if($next == "") echo(json_encode(array("response"=>"false")));
		else echo(json_encode(array("response"=>"true", "md5"=>$next)));
		break;
	case "load-player":
		$config = Configs::get(NULL,$location,"next_on_showplan");
		$audio = Audio::get_by_md5($config->get_val());
		$config->set_val("");

		echo(json_encode(array("response"=>"success","title"=>$audio->get_title(),"artist"=>$audio->get_artists_str())));
		break;
}
?>