<?php


if(Session::is_developer()){
	if($_REQUEST["id"]) {
		$fault = Faults::get_by_id($_REQUEST["id"]);
		if($fault->delete()) {
			exit(json_encode(array('response' => 'success')));
		} else {
			http_response_code(403);
			exit(json_encode(array('error' => 'Unknown error.')));
		}
	}
} else {
	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));
}
?>