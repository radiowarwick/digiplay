<?php
class Artists {
	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM artists WHERE id = :id", "Artist". false, $id); }
	public static function get_by_name($name) { return DigiplayDB::select("* FROM artists WHERE name = :name", "Artist", false, $name); }
	public static function get_by_audio($audio) { return DigiplayDB::select("artists.* FROM artists INNER JOIN audioartists ON (artists.id = audioartists.artistid) WHERE audioartists.audioid = ".$audio->get_id(), "Artist", true); }

	public static function count() { return DigiplayDB::select("count(id) FROM artists;"); }
}