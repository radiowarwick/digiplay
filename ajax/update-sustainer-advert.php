<?php

if(Session::is_group_user('Sustainer Admin')){

	if(!($advert = Adverts::get_by_id((int) $_REQUEST["advertid"]))) exit(json_encode(array('error' => 'Invalid advert ID.')));

	$advert->set_sustainer($_REQUEST['sue']);

	$advert->save();

	if(Errors::occured()) { 

		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();

	} else {

		exit(json_encode(array('response' => 'success', 'id' => $playlist->get_id())));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>