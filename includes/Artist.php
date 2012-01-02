<?php
class Artist {
	protected $id;
	protected $name;
	protected $alt_name;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_alt_name() { return $this->alt_name; }

	public function set_name($name) { $this->name = $name; }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::query("UPDATE artists SET name = '".pg_escape_string($this->name)."' WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO artists (name) VALUES ('".pg_escape_string($this->name)."') RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}

	/* Extended functions */
	public function add_to_track($track_id) {
		if(!$this->id) $this->save();
		$sql = "INSERT INTO audioartists (audioid,artistid) VALUES (".$track_id.",".$this->id.");";
		$result = DigiplayDB::query($sql);
		return (bool) $result;
	}

	public function del_from_track($track_id) {
		$sql = "DELETE FROM audioartists WHERE audioid = ".$track_id." AND artistid = ".$this->id.";";
		$result = DigiplayDB::query($sql);
		$remaining = DigiplayDB::query("SELECT * FROM audioartists WHERE artistid = ".$this->id.";");
		if(!pg_fetch_array($remaining)) DigiplayDB::query("DELETE FROM artists WHERE id = ".$this->id.";");
		return (bool) $result;
	}
}
?>