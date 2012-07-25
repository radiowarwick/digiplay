<?php
class Sustainer {
	public function get_total_tracks(){
		$tracks = pg_fetch_assoc(DigiplayDB::query("SELECT COUNT(id) FROM audio WHERE sustainer = 't';"));
		return $tracks["count"];
	}

	public function get_total_length() {
		$length = pg_fetch_assoc(DigiplayDB::query("SELECT SUM(length_smpl) FROM audio WHERE sustainer ='t';"));
		$length = $length["sum"] / 44100;
		return $length;
	}

	public function get_total_length_formatted() {
		$time_arr = Time::seconds_to_dhms(Sustainer::get_total_length());
		$time_str = ($time_arr["days"])? $time_arr["days"]." days, " : "";
		$time_str .= ($time_arr["hours"])? $time_arr["hours"]." hours, " : "";
		$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]." minutes, " : "";
		$time_str .= ($time_arr["seconds"])? sprintf('%02d',$time_arr["seconds"])." seconds " : "00s ";
		return $time_str;
	}
}
?>
