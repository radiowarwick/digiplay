<?php
class AudiowallItem {
	protected $id;
	protected $audio_id;
	protected $style_id;
	protected $item;
	protected $wall_id;
	protected $text;
	
	public function get_id(){ return $this->id; }
	public function get_audio_id(){ return $this->audio_id; }
	public function get_style_id(){ return $this->style_id; }
	public function get_item(){ return $this->item; }
	public function get_wall_id(){ return $this->wall_id; }
	public function get_text(){ return str_replace("\n", "<br />", $this->text); }

	public function set_audio_id($audio_id){ $this->audio_id = $audio_id; }
	public function set_style_id($style_id){ $this->style_id = $style_id; }
	public function set_item($item){ $this->item = $item; }
	public function set_wall_id($wall_id){ $this->wall_id = $wall_id; }
	public function set_text($text){ $this->text = $text; }
	
	/* Extended functions */
	public function get_style() { return AudiowallStyles::get_by_id($this->style_id); }
	public function get_audio() { return Audio::get_by_id($this->audio_id); }

	public function save() {
		$query = "INSERT INTO aw_items (audio_id, style_id, item, wall_id, text) VALUES ('".$this->audio_id."', '".$this->style_id."', '".$this->item."', '".$this->wall_id."', '".pg_escape_string($this->text)."')";
		$result = DigiplayDB::query($query);
	}

}
?>