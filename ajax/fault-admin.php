<?php
if(!Session::is_developer()) {
	http_response_code(403);
	exit();
}
switch($_REQUEST["action"]) {
	case "assign-fault":
		$fault = Faults::get_by_id($_REQUEST['id']);
		if ($fault) {
			$fault->set_assignedto($_REQUEST['assign']);
			if ($fault->save()) {
				exit(json_encode(array('response' => 'success')));	
			} else {
				exit(json_encode(array('error' => 'Unknown error.')));
			}
		}
		break;
	case "update-status":
		$fault = Faults::get_by_id($_REQUEST['id']);
		if ($fault) {
			$fault->set_status($_REQUEST['status']);
			if ($fault->save()) {
				exit(json_encode(array('response' => 'success')));	
			} else {
				exit(json_encode(array('error' => 'Unknown error.')));
			}
		}
		break;
	case "del-fault":
		$fault = Faults::get_by_id($_REQUEST["id"]);
		if ($fault) {
			if($fault->delete()) {
				exit(json_encode(array('response' => 'success')));
			} else {
				exit(json_encode(array('error' => 'Unknown error.')));
			}
		}
		break;
}

?>