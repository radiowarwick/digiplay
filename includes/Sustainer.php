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

	public static function get_queue() { return DigiplayDB::select("sustschedule.id, sustschedule.audioid, audio.title, artists.name AS artist, albums.name AS album FROM sustschedule INNER JOIN audio ON sustschedule.audioid = audio.id INNER JOIN audioartists ON audio.id = audioartists.audioid INNER JOIN artists ON audioartists.artistid = artists.id INNER JOIN albums ON audio.music_album = albums.id ORDER BY sustschedule.id ASC LIMIT 10"); }

	public static function get_log() { return DigiplayDB::select("audio.title, artists.name AS artist, users.username, sustlog.timestamp FROM sustlog INNER JOIN audio ON sustlog.audioid = audio.id INNER JOIN audioartists ON audio.id = audioartists.audioid INNER JOIN artists ON audioartists.artistid = artists.id INNER JOIN users ON sustlog.userid = users.id ORDER BY timestamp ASC LIMIT 10"); }
}
?>
