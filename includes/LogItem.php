<?php
Class LogItem {
	protected $id;
	protected $location;
	protected $userid;
	protected $datetime;
	protected $track_title;
	protected $track_artist;

	public function get_id() { return $this->id; }
	public function get_location() { return $this->location; }
	public function get_user() { return Users::get_by_id($this->userid); }
	public function get_datetime() { return $this->datetime; }
	public function get_track_title() { return $this->track_title; }
	public function get_track_artist() { return $this->track_artist; }

}
?>