<?php
class SustainerSlot {
	protected $id;
	protected $day;
	protected $time;
	protected $playlist_id;
	
	public function get_id(){ return $this->id; }
	public function get_day(){ return $this->day; }
	public function get_time(){ return $this->time; }
	public function get_playlist_id(){ return $this->playlist_id; }

	public function set_day($day){ $this->day = $day; }
	public function set_time($time){ $this->time = $time; }
	public function set_playlist_id($playlist_id){ $this->playlist_id = $playlist_id; }
	
	/* Extended functions */
	/*public function get_style() { return AudiowallStyles::get_by_id($this->style_id); }
	public function get_audio() { return Audio::get_by_id($this->audio_id); }

	public function save() {
		$query = "INSERT INTO aw_items (audio_id, style_id, item, wall_id, text) VALUES ('".$this->audio_id."', '".$this->style_id."', '".$this->item."', '".$this->wall_id."', '".pg_escape_string($this->text)."')";
		$result = DigiplayDB::query($query);
	}*/

}
?>