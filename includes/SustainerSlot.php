<?php
class SustainerSlot {
	protected $id;
	protected $day;
	protected $time;
	protected $playlistid;
	
	public function get_id(){ return $this->id; }
	public function get_day(){ return $this->day; }
	public function get_time(){ return $this->time; }
	public function get_playlist_id(){ return $this->playlistid; }

	public function set_day($day){ $this->day = $day; }
	public function set_time($time){ $this->time = $time; }
	public function set_playlist_id($playlist_id){ $this->playlistid = $playlist_id; }
	
	/* Extended functions */
	/*public function get_style() { return AudiowallStyles::get_by_id($this->style_id); }
	public function get_audio() { return Audio::get_by_id($this->audio_id); }*/

	public function save() {
		if(isset($this->id)) DigiplayDB::update("sustslots", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("sustslots", get_object_vars($this), "id");
		return $this->id;
	}

}
?>