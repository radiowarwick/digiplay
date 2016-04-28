<?php
class AudiowallSets{

	public static function get($id) {
		return self::get_by_id($id);
	}

	public static function get_by_id($id) {
		if (!is_int($id)){
		    return null;
		}
		return DigiplayDB::select("* FROM aw_sets WHERE id = :id", "AudiowallSet", false, array(':id' => $id));
	}
	
	public static function get_all() {
		return DigiplayDB::select("* FROM aw_sets ORDER BY upper(name) ASC", "AudiowallSet", true);
	}

	public static function count_by_user() {
		return DigiplayDB::select("count(set_id) FROM aw_sets_owner WHERE user_id = :user_id", null, false, array(':user_id' => Session::get_id()));
	}

	public static function count() { return DigiplayDB::select("count(id) from aw_sets", null, false); }
}
?>
