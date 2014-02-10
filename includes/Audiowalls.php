<?php
class Audiowalls {

	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) {
		return DigiplayDB::select("* FROM aw_walls WHERE id = :id", "Audiowall", false, array(':id' => $id));
	}
	
	public static function get_by_set($set) {
		return DigiplayDB::select("* FROM aw_walls WHERE set_id = :set_id ORDER BY page ASC;", "Audiowall", true, array(":set_id" => $set->get_id()));
	}
}
?>