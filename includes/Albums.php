<?php
class Albums {
	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM albums WHERE id = :id", "Album", false, $id); }
	public static function get_by_name($name) { return DigiplayDB::select("* FROM albums WHERE name = :name", "Album", false, $name); }
	public static function get_by_audio_id($audio_id) { return DigiplayDB::select("albums.* FROM albums INNER JOIN audio ON (albums.id = audio.music_album) WHERE audio.id = :id", "Album", false, $audio_id); }

	public static function count() { return DigiplayDB::select("count(id) FROM albums;"); }
}