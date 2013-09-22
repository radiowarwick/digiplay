<?php
class Playlists {
	public function get($id) { return Playlists::get_by_id($id); }

	public function get_by_id($id) { return DigiplayDB::select("* FROM playlists WHERE id = ".$id, "Playlist"); }
	public function get_all($sustainer = true) { return DigiplayDB::select("* FROM playlists".($sustainer? "" : " WHERE id > 0")." ORDER BY sortorder ASC;", "Playlist"); }

	public function get_by_track($track) { return DigiplayDB::select("playlists.* FROM playlists INNER JOIN audioplaylists ON playlists.id = audioplaylists.playlistid WHERE audioplaylists.audioid = ".$track->get_id().";", "Playlist", true); }
}
?>