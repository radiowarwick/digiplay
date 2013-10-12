<?php

if(Session::is_developer()){
		$fault = Faults::get_by_id($_REQUEST['id']);

		// Update status to new value
		$fault->set_status($_REQUEST['status']);

		if ($fault->save()) {
			header('Location: http://dev.radio.warwick.ac.uk/dps/jamesvh/information/manage/');
			exit();		
		} else {
			echo "error ".pg_last_error();
		}
} else {
	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));
}		

?>
