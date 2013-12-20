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
		if($this->id) DigiplayDB::update("artists", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("artists", get_object_vars($this), "id");
		return $this->id;
	}

	/* Extended functions */
	public function add_to_track($track) {
		if(!$this->id) $this->save();
		return DigiplayDB::insert("audioartists", array("audioid" => $track->get_id(), "artistid" => $this->id));
	}

	public function del_from_track($track) {
		$result = DigiplayDB::delete("audioartists", "audioid = ".$track->get_id()." AND artistid = ".$this->id);
		$remaining = DigiplayDB::select("* FROM audioartists WHERE artistid = ".$this->id.";");
		if(!$remaining) $result = DigiplayDB::delete("artists", "id = ".$this->id);
		return (bool) $result;
	}
}
?>