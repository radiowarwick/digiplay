<?php
class Playlists {
	public static function get($id) { return Playlists::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM playlists WHERE id = ".$id, "Playlist"); }
	public static function get_all($sustainer = true) { return DigiplayDB::select("* FROM playlists".($sustainer? "" : " WHERE sustainer = 'f'")." ORDER BY sortorder ASC;", "Playlist"); }

	public static function get_sustainer() { return DigiplayDB::select("* FROM playlists WHERE sustainer = 't' ORDER BY sortorder ASC;", "Playlist"); }	

	public static function get_by_track($track) { return DigiplayDB::select("playlists.* FROM playlists INNER JOIN audioplaylists ON playlists.id = audioplaylists.playlistid WHERE audioplaylists.audioid = ".$track->get_id().";", "Playlist", true); }
}
?>