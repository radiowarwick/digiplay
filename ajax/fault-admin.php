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
			
				$systemStatus = "This fault has been assigned to ".$fault->get_real_assignedto($_REQUEST['assign']);

				$comment = new Comment();
				$comment->set_faultid($_REQUEST['id']);
				$comment->set_author(-1);
				$comment->set_comment($systemStatus);
				$comment->set_postdate(time());
				$comment->save();
				
				exit(json_encode(array('response' => 'success')));	
			} else {
				exit(json_encode(array('error' => 'Unknown error.')));
			}
		}
		break;
	case "update-status":
		$fault = Faults::get_by_id($_REQUEST['id']);
		if ($fault) {
			$oldStatus = $fault->get_real_status();
			$fault->set_status($_REQUEST['status']);
			if ($fault->save()) {

				$systemStatus = "Fault status has been updated from ".strtolower($oldStatus)." to ".strtolower($fault->get_real_status()).".";

				$comment = new Comment();
				$comment->set_faultid($_REQUEST['id']);
				$comment->set_author(-1);
				$comment->set_comment($systemStatus);
				$comment->set_postdate(time());
				$comment->save();

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