<?php

		$fault = new Fault();
		$fault->set_author(Session::get_id());
		$fault->set_content($_REQUEST['content']);
		$fault->set_status(4);
		if ($fault->save()) {
			header('Location: http://dev.radio.warwick.ac.uk/dps/jamesvh/information/faults/');
			exit();		
		} else {
			echo "error ".pg_last_error();
		}

		

?>
