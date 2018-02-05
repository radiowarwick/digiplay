<?php

if(Session::is_user()){

	// Require a description for all audiowalls
	// Makes it easier for audiowall admins to manage 
	if ($_REQUEST["awdescription"] == "") {
		http_response_code(400);
		exit(json_encode(array("error" => "Audiowall description missing", "detail" => "You must provide a description for the audiowall")));
		Errors::clear();
	}

	// Query number of audiowalls that the user currently has
	// If an audiowall already exists for that user, deny creation
	$numberOfAudiowalls = AudiowallSets::count_by_user();
	if ($numberOfAudiowalls > 1 && !(Session::is_group_user('Audiowalls Admin'))) {
		http_response_code(400);
		exit(json_encode(array("error" => "Audiowall limit exceeded", "detail" => "You are limited to two audiowalls")));
		Errors::clear();
	}

	$aw_set = new AudiowallSet();
	$aw_set->set_name(pg_escape_string($_REQUEST["awname"]));
	$aw_set->set_description(pg_escape_string($_REQUEST["awdescription"]));
	$aw_set->save();

	// Add audiowall owner to the database
	$data = array(	
		'user_id' => Session::get_id(),
		'set_id' => $aw_set->get_id()
	);
	DigiplayDB::insert("aw_sets_owner", $data);

	// Add audiowall permissions to current user
	// The bitmask is as follows (view, edit, delete) where a value of 1 grants the permission
	// INSERT INTO aw_sets_permissions (user_id, set_id, permissions) VALUES (Session::get_id(), $aw_set->get_id(), '111'); 
	$data = array(	
		'user_id' => Session::get_id(),
		'set_id' => $aw_set->get_id(),
		'permissions' => '111'
	);
	DigiplayDB::insert("aw_sets_permissions", $data);

	if(Errors::occured()) { 

		http_response_code(400);
		exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
		Errors::clear();

	} else {

		exit(json_encode(array('response' => 'success', 'id' => $aw_set->get_id())));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>
