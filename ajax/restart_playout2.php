<?php
if(Session::is_user()){

	shell_exec("/usr/scripts/restart_po2_php");

	if(Errors::occured()) { 

		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();

	} else {

		exit(json_encode(array('response' => 'success', 'id' => 1)));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}
?>
