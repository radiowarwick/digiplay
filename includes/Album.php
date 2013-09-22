<?php
class Album {
	protected $id;
	protected $name;
	protected $alt_name;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_alt_name() { return $this->alt_name; }

	public function set_name($name) { $this->name = $name; }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::update("albums", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("albums", get_object_vars($this));
		return $this->id;
	}
}
?>