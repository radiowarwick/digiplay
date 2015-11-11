<?php

if(Session::is_user()){

	$a = AudiowallSets::get_by_id($_REQUEST['setid']);

	if($a->user_can_edit()) {

		$walls = $a->get_walls();

		$w = 0;
		foreach($walls as $wall) {
			$w++;
		}
		if($_REQUEST['name'] == "") {
			$name = "New Page";
		} else {
			$name = $_REQUEST['name'];
		}
		if($_REQUEST['desc'] == "") {
			$desc = "New Page";
		}
		else {
			$desc = $_REQUEST['desc'];
		}

		$table = "aw_walls";
		$data = array('id' => NULL,
					'name' => $name,
					'set_id' => $_REQUEST['setid'],
					'page' => $w,
					'description' => $desc);
		DigiplayDB::insert($table, $data);

		if(Errors::occured()) { 

			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();

		} else {

			exit(json_encode(array('response' => 'success', 'id' => $a->get_id())));

		}

	} else {

		http_response_code(403);
		exit(json_encode(array('error' => 'Permission denied.')));

	}

} else {

	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));

}

?>
