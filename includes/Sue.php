<?php
class Sue {
	public function get_total_tracks(){
		$tracks = pg_fetch_assoc(DigiplayDB::query("SELECT COUNT(id) FROM audio WHERE sustainer = 't';"));
		return $tracks["count"];
	}

	public function get_total_length() {
		$length = pg_fetch_assoc(DigiplayDB::query("SELECT SUM(length_smpl) FROM audio WHERE sustainer ='t';"));
		$length = $length["sum"] / 44100;
		return $length;
	}
}
?>