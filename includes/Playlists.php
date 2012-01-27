<?php
class Playlists {
	public function get($id) { 
		return Playlists::get_by_id($id);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM playlists WHERE id = ".$id);
		return pg_fetch_object($result,NULL,"Playlist");
	}

	public function get_all() {
		$playlists = array();
		$result = DigiplayDB::query("SELECT * FROM playlists ORDER BY sortorder ASC;");
		while($object = pg_fetch_object($result,NULL,"Playlist"))
                 $playlists[] = $object;
    	return $playlists;
	}
}
?>