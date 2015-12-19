<?php

		$fault = new Fault();
		// Get the current users ID for the submission
		$fault->set_author(Session::get_id());
		// Grab content
		$fault->set_content($_REQUEST['content']);
		// Set default status to unread
		$fault->set_status(1);
		// Current time and date added to record
		$fault->set_postdate(time());

		$fault->save();

		if(Errors::occured()) { 

			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();

		} else {

			exit(json_encode(array('response' => 'success', 'id' => $fault->get_id())));

		}

		

?>
