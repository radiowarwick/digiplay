<?php
class Tracks{

	public function get($id) {
		return self::get_by_id($id);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM audio WHERE id = ".$id);
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Track");
		} else return false;
	}

	public function get_total_tracks() {
		$type = AudioTypes::get("music")->get_id();
		$type = $type["id"];
		$tracks = pg_fetch_assoc(DigiplayDB::query("SELECT COUNT(id) FROM audio WHERE type = ".$type));
		return $tracks["count"];
	}

	public function get_total_length() {
		$type = AudioTypes::get("music")->get_id();
		$type = $type["id"];
		$length = pg_fetch_assoc(DigiplayDB::query("SELECT SUM(length_smpl) FROM audio WHERE type = ".$type));
		$length = $length["sum"] / 44100;
		return $length;
	}

	public function get_playlisted($playlist = NULL) {
		$tracks = array();
		if($playlist) {
			$result = DigiplayDB::query("SELECT audio.* FROM audio INNER JOIN audioplaylists ON (audio.id = audioplaylists.audioid) WHERE audioplaylists.playlistid = ".$playlist->get_id());
		} else {
			$result = DigiplayDB::query("SELECT audio.* FROM audio INNER JOIN audioplaylists ON (audio.id = audioplaylists.audioid);");
		}
		while($object = pg_fetch_object($result,NULL,"Track"))
                 $tracks[] = $object;
    	return $tracks;
	}

	public function get_newest($num=10) {
		$tracks = array();
		$result = DigiplayDB::query("SELECT id FROM v_audio_music WHERE dir = 2 ORDER BY id DESC LIMIT ".$num.";");
		while($track = pg_fetch_array($result)) 
            $tracks[] = Tracks::get_by_id($track[0]);
    	return $tracks;
	}

	public function get_flagged() {
		$type = AudioTypes::get("music")->get_id();
		$tracks = array();
		$result = DigiplayDB::query("SELECT * FROM audio WHERE type = ".$type." AND flagged = 't' ORDER BY import_date DESC;");
		while($object = pg_fetch_object($result,NULL,"Track"))
            $tracks[] = $object;
    	return $tracks;
	}

	public function get_censored($limit = 0,$offset = 0) {
		$limit = ($limit > 0)? " LIMIT ".$limit : "";
		$offset = ($offset > 0)? " OFFSET ".$offset : "";
		$tracks = array();
		$result = DigiplayDB::query("SELECT id FROM v_audio_music WHERE dir = 2 AND censor = 't' ORDER BY id DESC".$limit.$offset);
		while($track = pg_fetch_array($result)) 
            $tracks[] = Tracks::get_by_id($track[0]);
    	return $tracks;
	}

	public function count_censored() {
		return pg_fetch_result(DigiplayDB::query("SELECT COUNT(id) FROM v_audio_music WHERE dir = 2 AND censor = 't';"),NULL,0);
	}
	
	public function get_tracks_of_the_day($count = 1){
		$today = mktime(0, 0, 0, (int)date("n"),(int)date("j"), (int)date("Y"));
		srand($today/pi());
		$trackcount = pg_fetch_result(DigiplayDB::query("SELECT count(*) FROM audio INNER JOIN audiodir ON audio.id=audiodir.audioid WHERE audio.import_date < ".$today." AND audio.type = 1 AND audiodir.dirid = 2;"),0);
		$tracks = array();
		for($i = 1; $i <= $count; $i++){
			$track = rand(0,$trackcount);
			$sql = "SELECT audio.id FROM audio INNER JOIN audiodir ON audio.id=audiodir.audioid WHERE audio.import_date < ".$today." AND audio.type = 1 AND audiodir.dirid = 2 ORDER BY audio.id LIMIT 1 OFFSET ".$track.";";
			$tracks[] = Tracks::get_by_id(pg_fetch_result(DigiplayDB::query($sql),0));
		}
		return $tracks;
	}
}
?>