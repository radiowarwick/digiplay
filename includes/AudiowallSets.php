<?php
class AudiowallSets{

	public function get($id) {
		return self::get_by_id($id);
	}

	public function get_by_id($id) {
		return DigiplayDB::select("* FROM aw_sets WHERE id = :id", "AudiowallSet", false, array(':id' => $id));
	}
	
	public function get_all() {
		return DigiplayDB::select("* FROM aw_sets ORDER BY upper(name) ASC", "AudiowallSet", true);
	}
}
?>