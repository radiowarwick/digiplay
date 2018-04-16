<?php
if(Session::is_group_user('Playlist Admin')){
	if(!isset($_REQUEST['id'])) {
		if(!is_null($_REQUEST['name'])) {
			$playlist = new Playlist();
			$playlist->set_name($_REQUEST['name']);

			if(isset($_REQUEST["sue"]) && $_REQUEST["sue"] == "true") {
				if(isset($_REQUEST["color"]))
					$color = $_REQUEST["color"];
				else
					$color = "#ffffff";
				$sue = "t";
			}
			else {
				$sue = "f";
				$color = "#ffffff";
			}
			$playlist->set_sustainer($sue);

			$playlist->save();

			$colorData = array("playlistid" => $playlist->get_id(), "colour" => substr($color, 1));
			DigiplayDB::insert("playlistcolours", $colorData);

			if(Errors::occured()) { 
				http_response_code(400);
				exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
				Errors::clear();
			} else {
				exit(json_encode(array('response' => 'success', 'id' => $playlist->get_id())));
			}
		} else {
			exit(json_encode(array('error' => 'No name specified for playlist.')));
		}
	} else {
		if(!($playlist = Playlists::get_by_id($_REQUEST['id']))) exit(json_encode(array('error' => 'Invalid playlist ID.')));
		$playlist->set_name($_REQUEST['name']);

		if(isset($_REQUEST["sue"]) && $_REQUEST["sue"] == "true") {
			$playlist->set_sustainer("t");
			$color = $_REQUEST["color"];
		}
		else {
			$playlist->set_sustainer("f");
			$color = "#ffffff";
		}

		$playlist->save();

		if(is_null(DigiplayDB::select("colour FROM playlistcolours WHERE playlistid = " . $playlist->get_id()))) {
			$colorData = array("playlistid" => $playlist->get_id(), "colour" => substr($color, 1));
			DigiplayDB::insert("playlistcolours", $colorData);
		}
		else
			DigiplayDB::update("playlistcolours", array("colour" => substr($color, 1)), "playlistid=" . $playlist->get_id());

		if(Errors::occured()) { 
			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();
		} else {
			exit(json_encode(array('response' => 'success', 'id' => $playlist->get_id())));
		}
	}
} else {
	http_response_code(403);
	exit(json_encode(array('error' => 'Permission denied.')));
}
?>
