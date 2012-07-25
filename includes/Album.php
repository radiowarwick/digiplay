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
		if($this->id) DigiplayDB::query("UPDATE albums SET name = '".pg_escape_string($this->name)."' WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO albums (name) VALUES ('".pg_escape_string($this->name)."') RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}
}
?>