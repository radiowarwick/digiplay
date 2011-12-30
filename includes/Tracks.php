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

	public function get_playlisted() {
		$tracks = array();
		$result = DigiplayDB::query("SELECT audio.* FROM audio INNER JOIN audioplaylists ON (audio.id = audioplaylists.audioid);");
		while($object = pg_fetch_object($result,NULL,"Track"))
                 $tracks[] = $object;
    	return $tracks;
	}

	public function get_newest($num=10) {
		$type = AudioTypes::get("music")->get_id();
		$tracks = array();
		$result = DigiplayDB::query("SELECT * FROM audio WHERE type = ".$type." ORDER BY import_date DESC LIMIT ".$num.";");
		while($object = pg_fetch_object($result,NULL,"Track"))
                 $tracks[] = $object;
    	return $tracks;
	}
}
?>