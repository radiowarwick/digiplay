<?php
Class LogItem {
	protected $id;
	protected $location;
	protected $userid;
	protected $datetime;
	protected $track_title;
	protected $track_artist;
	protected $audioid;

	public function get_id() { return $this->id; }
	public function get_location() { return Locations::get_by_id($this->location); }
	public function get_user() { return Users::get_by_id($this->userid); }
	public function get_datetime() { return $this->datetime; }
	public function get_track_title() { return $this->track_title; }
	public function get_track_artist() { return $this->track_artist; }
	public function get_audio() { return Audio::get_by_id($audioid); }

	public function set_location($location) { $this->location = $location; }
	public function set_user($user) { $this->userid = $user->get_id(); }
	public function set_datetime($datetime) { $this->datetime = $datetime; }
	public function set_track_title($track_title) { $this->track_title = $track_title; }
	public function set_track_artist($track_artist) { $this->track_artist = $track_artist; }
	public function set_audio($audio) { $this->audioid = $audio->get_id(); }

	public function save() {
		if(!$this->track_title) return false;
		if($this->id) DigiplayDB::update("log", get_object_vars($this), "id = ".$this->id.";");
		else {
			$this->datetime = time();
			if($this->userid == NULL) $this->userid = 0;
			$this->id = DigiplayDB::insert("log", get_object_vars($this), "id");
		}
		return $this->id;
	}
}
?>