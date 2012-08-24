<?php
require_once("pre.php");

$track = Tracks::get_by_id($_REQUEST["id"]);
if($_REQUEST["flag"]) {
	if($track->is_flagged()) {
		$track->set_flagged(false);
		$response = "unflagged";
	} else {
		$track->set_flagged(true);
		$response = "flagged";
	}
}
	
$result = $track->save();
if(!$result) {
	http_response_code(400);
	exit(json_encode(array("error" => "Something is incorrect.  You may have discovered a bug!")));
}

exit(json_encode(array("response"=>$response)));
?>