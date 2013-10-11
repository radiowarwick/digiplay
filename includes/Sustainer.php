<?php
class Sustainer {
	public static function get_total_tracks() { return DigiplayDB::select("COUNT(audioid) FROM audioplaylists WHERE playlistid = 0;"); }

	public static function get_total_length() { return DigiplayDB::select("SUM(length_smpl) FROM audio INNER JOIN audioplaylists ON (audio.id = audioplaylists.audioid) WHERE audioplaylists.playlistid = 0;") / 44100; }

	public static function get_total_length_formatted() {
		$time_arr = Time::seconds_to_dhms(Sustainer::get_total_length());
		$time_str = ($time_arr["days"])? $time_arr["days"]." days, " : "";
		$time_str .= ($time_arr["hours"])? $time_arr["hours"]." hours, " : "";
		$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]." minutes, " : "";
		$time_str .= ($time_arr["seconds"])? sprintf('%02d',$time_arr["seconds"])." seconds " : "00s ";
		return $time_str;
	}
}
?>
