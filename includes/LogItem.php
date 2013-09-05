<?php
Class LogItem {
	protected $id;
	protected $location;
	protected $userid;
	protected $datetime;
	protected $track_title;
	protected $track_artist;

	public function get_id() { return $this->id; }
	public function get_location() { return Locations::get_by_id($this->location); }
	public function get_user() { return Users::get_by_id($this->userid); }
	public function get_datetime() { return $this->datetime; }
	public function get_track_title() { return $this->track_title; }
	public function get_track_artist() { return $this->track_artist; }

	public function set_location($location) { $this->location = $location; }
	public function set_user($user) { $this->userid = $user->get_id(); }
	public function set_datetime($datetime) { $this->datetime = $datetime; }
	public function set_track_title($track_title) { $this->track_title = $track_title; }
	public function set_track_artist($track_artist) { $this->track_artist = $track_artist; }

	public function save() {
		if(!$this->track_title) return false;
		if($this->id) DigiplayDB::query("UPDATE log SET location = ".$this->location->get_id().", track_title = '".pg_escape_string($this->track_title)."', track_artist = '".pg_escape_string($this->track_artist)." WHERE id = ".$this->id.";");
		else {
			$this->datetime = time();
			if($this->userid == NULL) $this->userid = 0;
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO log (location,userid,datetime,track_title,track_artist) VALUES (".$this->location->get_id().",".$this->userid.",".$this->datetime.",'".pg_escape_string($this->track_title)."','".pg_escape_string($this->track_artist)."') RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}
}
?>