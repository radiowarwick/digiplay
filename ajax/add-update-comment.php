<?php

		$comment = new Comment();

		// Relate comment to fault ID
		$comment->set_faultid($_REQUEST['faultid']);

		// Get the current users ID for the submission
		// If it is a system message, use -1
		if (!isset($_REQUEST['system'])) {
			$comment->set_author(-1);
		} else {
			$comment->set_author(Session::get_id());
		}
		
		// Grab content
		$comment->set_comment($_REQUEST['comment']);

		// Current time and date added to record
		$comment->set_postdate(time());

		if ($comment) {
			if($comment->save()) {
				exit(json_encode(array('response' => 'success')));
			} else {
				exit(json_encode(array('error' => 'Unknown error.')));
			}
		}

		

?>
