<?php
Class StatusInfo {
	protected $id;
	protected $status;
	protected $permalink;
	protected $colour;

	public function get_id() { return $this->id; }
	public function get_status() { return $this->status; }
	public function get_permalink() { return $this->permalink; }
	public function get_colour() { return $this->colour; }

	public function set_status($status) { $this->status = $status; }
	public function set_permalink($permalink) { $this->permalink = $permalink; }
	public function set_colour($colour) { $this->colour = $colour; }
	
	public function save() {
		if(!$this->status) return false;
		if($this->id) DigiplayDB::update("info_status_statuses", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("info_status_statuses", get_object_vars($this), "id");
		return $this->id;
	}

}
?>
