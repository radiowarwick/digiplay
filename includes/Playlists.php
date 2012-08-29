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

	public function get_by_track($track) {
		$playlists = array();
		$result = DigiplayDB::query("SELECT playlists.* FROM playlists INNER JOIN audioplaylists ON playlists.id = audioplaylists.playlistid WHERE audioplaylists.audioid = ".$track->get_id().";");
		while($object = pg_fetch_object($result,NULL,"Playlist"))
                 $playlists[] = $object;
    	return $playlists;
	}
}
?>