<?php
class AudiowallSets{

	public static function get($id) {
		return self::get_by_id($id);
	}

	public static function get_by_id($id) {
		return DigiplayDB::select("* FROM aw_sets WHERE id = :id", "AudiowallSet", false, array(':id' => $id));
	}
	
	public static function get_all() {
		return DigiplayDB::select("* FROM aw_sets ORDER BY upper(name) ASC", "AudiowallSet", true);
	}
}
?>