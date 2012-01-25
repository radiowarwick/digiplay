<?php
class Artists {
	public function get($id) {
		return self::get_by_id($id);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM artists WHERE id = ".$id);
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Artist");
		} else return false;
	}

	public function get_by_name($name) {
		$result = DigiplayDB::query("SELECT * FROM artists WHERE name = '".$name."'");
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Artist");
		} else return false;
	}

	public function get_by_audio_id($audio_id) {
		$artists = array();
		$result = DigiplayDB::query("SELECT artists.* FROM artists INNER JOIN audioartists ON (artists.id = audioartists.artistid) WHERE audioartists.audioid = ".$audio_id); 
		while($object = pg_fetch_object($result,NULL,"Artist"))
                 $artists[] = $object;
    	return ((count($artists) > 0)? $artists : false);
	}

	public function count() {
		$result = DigiplayDB::query("SELECT count(id) FROM artists;");
		return pg_fetch_result($result,NULL,0);
	}
}