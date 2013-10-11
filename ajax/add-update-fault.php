<?php

		$fault = new Fault();
		// Get the current users ID for the submission
		$fault->set_author(Session::get_id());
		// Grab content
		$fault->set_content($_REQUEST['content']);
		// Set default status to unread
		$fault->set_status(4);
		// Current time and date added to record
		$fault->set_postdate(time());

		if ($fault->save()) {
			header('Location: http://dev.radio.warwick.ac.uk/dps/jamesvh/information/faults/');
			exit();		
		} else {
			echo "error ".pg_last_error();
		}

		

?>
