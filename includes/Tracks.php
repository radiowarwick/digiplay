<?php
class Tracks{

	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM audio WHERE id = ".$id, "Track"); }
	public static function get_by_md5($md5) { return DigiplayDB::select("* FROM audio WHERE md5 = '".$md5."'", "Track"); }

	public static function get_total_tracks() { return DigiplayDB::select("COUNT(id) FROM audio WHERE type = ".AudioTypes::get("Music")->get_id()); }
	public static function get_total_length() { return DigiplayDB::select("SUM(length_smpl) FROM audio WHERE type = ".AudioTypes::get("Music")->get_id()) / 44100; }

	public static function get_playlisted($playlist = NULL,$limit = 0,$offset = 0) {
		$limit = ($limit > 0)? " LIMIT ".$limit : "";
		$offset = ($offset > 0)? " OFFSET ".$offset : "";
		$tracks = array();
		if($playlist) return DigiplayDB::select("audio.* FROM audio INNER JOIN audioplaylists ON (audio.id = audioplaylists.audioid) WHERE audioplaylists.playlistid = ".$playlist->get_id().$limit.$offset, "Track", true);
		else return DigiplayDB::select("audio.* FROM audio INNER JOIN audioplaylists ON (audio.id = audioplaylists.audioid)".$limit.$offset, "Track", true);
	}

	public static function get_newest($num=10) { return DigiplayDB::select("* FROM audio WHERE type = ".AudioTypes::get("Music")->get_id()." ORDER BY id DESC LIMIT ".$num.";", "Track", true); }
	public static function get_flagged() { return DigiplayDB::select("* FROM audio WHERE type = ".AudioTypes::get("Music")->get_id()." AND flagged = 't' ORDER BY import_date DESC;", "Track", true); }

	public static function get_censored($limit = 0,$offset = 0) {
		$limit = ($limit > 0)? " LIMIT ".$limit : "";
		$offset = ($offset > 0)? " OFFSET ".$offset : "";
		return DigiplayDB::select("* FROM audio WHERE type = ".AudioTypes::get("Music")->get_id()." AND censor = 't' ORDER BY id DESC".$limit.$offset, "Track", true);
	}

	public static function count_censored() { return DigiplayDB::select("COUNT(id) FROM audio WHERE censor = 't';"); }
	
	public static function get_tracks_of_the_day($count = 1) {
		$today = mktime(0, 0, 0, (int)date("n"),(int)date("j"), (int)date("Y"));
		srand($today/pi());
		$trackcount = DigiplayDB::select("count(*) FROM audio INNER JOIN audiodir ON audio.id=audiodir.audioid WHERE audio.import_date < ".$today." AND audio.type = 1 AND audiodir.dirid = 2;");
		$tracks = array();
		for($i = 1; $i <= $count; $i++){
			$track = rand(0,$trackcount);
			$sql = 
			$tracks[] = self::get_by_id(DigiplayDB::select("audio.id FROM audio INNER JOIN audiodir ON audio.id=audiodir.audioid WHERE audio.import_date < ".$today." AND audio.type = 1 AND audiodir.dirid = 2 ORDER BY audio.id LIMIT 1 OFFSET ".$track.";"));
		}
		return $tracks;
	}
}
?>
