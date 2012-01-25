<?php
class Albums {
	public function get($id) {
		return self::get_by_id($id);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM albums WHERE id = ".$id);
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Album");
		} else return false;
	}

	public function get_by_name($name) {
		$result = DigiplayDB::query("SELECT * FROM albums WHERE name = '".$name."'");
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Album");
		} else return false;
	}

	public function get_by_audio_id($audio_id) {
		$result = DigiplayDB::query("SELECT albums.* FROM albums INNER JOIN audio ON (albums.id = audio.music_album) WHERE audio.id = ".$audio_id);
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Album");
		} else return false;
	}

	public function count() {
		$result = DigiplayDB::query("SELECT count(id) FROM albums;");
		return pg_fetch_result($result,NULL,0);
	}
}