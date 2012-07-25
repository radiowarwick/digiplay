<?php
class Keyword {
	protected $id;
	protected $name;

	public function get_id() { return $this->id; }
	public function get_text() { return $this->name; }

	public function set_text($text) { $this->name = $text; }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::query("UPDATE keywords SET name = '".pg_escape_string($this->name)."' WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO keywords (name) VALUES ('".pg_escape_string($this->name)."') RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}

	/* Extended functions */
	public function add_to_track($track_id) {
		if(!$this->id) $this->save();
		$sql = "INSERT INTO audiokeywords (audioid,keywordid) VALUES (".$track_id.",".$this->id.");";
		$result = DigiplayDB::query($sql);
		return (bool) $result;
	}

	public function del_from_track($track_id) {
		$sql = "DELETE FROM audiokeywords WHERE audioid = ".$track_id." AND keywordid = ".$this->id.";";
		$result = DigiplayDB::query($sql);
		$remaining = DigiplayDB::query("SELECT * FROM audiokeywords WHERE keywordid = ".$this->id.";");
		if(!pg_fetch_array($remaining)) DigiplayDB::query("DELETE FROM keywords WHERE id = ".$this->id.";");
		return (bool) $result;
	}
}
?>