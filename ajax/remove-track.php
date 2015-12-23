<?php
	if (Session::is_group_user("Music Admin")) {
		$track_id = (int) $_REQUEST["id"];
		$track = Audio::get_by_id($track_id);
		$md5 = $track->get_md5();
		$archive = $track->get_archive();
		$dir = $archive->get_localpath();
		$folder = $md5[0];
		$files = array(
			0 => ".flac",
			1 => ".xml"
		);
		$tables = array(
			0 => 'audioartists',
			1 => 'audiocomments',
			2 => 'audiodir',
			3 => 'audiogroups',
			4 => 'audiojinglepkgs',
			5 => 'audiokeywords',
			6 => 'audioplaylists',
			7 => 'audiousers'
		);
		$wherepre = "audioid = ".$track_id;
		$where = pg_escape_string($wherepre);
		$track_id_escaped = pg_escape_string($track_id);
		DigiplayDB::delete('audio', "id = ".$track_id_escaped);
		foreach ($tables as $table) {
		  	DigiplayDB::delete($table, $where);
		}
		foreach ($files as $file) {
			$path = $dir."/".$folder."/".$md5.$file; 
			$cmd = "rm ".$path;
			shell_exec($cmd);
		}

		if(Errors::occured()) { 
			http_response_code(400);
			exit(json_encode(array("error" => "Something went wrong. You may have discovered a bug!","detail" => Errors::report("array"))));
			Errors::clear();
		} else {
			exit(json_encode(array('response' => 'success', 'id' => 1)));
		}
	} else {
		http_response_code(403);
		exit(json_encode(array('error' => 'Permission denied.')));
	}
?>