<?php
class Keyword {
	protected $id;
	protected $name;

	public function get_id() { return $this->id; }
	public function get_text() { return $this->name; }

	public function set_text($text) { $this->name = $text; }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::update("keywords", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("keywords", get_object_vars($this), "id");
		return $this->id;
	}

	/* Extended functions */
	public function add_to_track($track) {
		if(!$this->id) $this->save();
		return DigiplayDB::insert("audiokeywords", array("audioid" => $track->get_id(), "keywordid" => $this->id));
	}

	public function del_from_track($track) {
		$result =  DigiplayDB::delete("audiokeywords", "audioid = ".$track->get_id()." AND keywordid = ".$this->id);
		$remaining = DigiplayDB::select("* FROM audiokeywords WHERE keywordid = ".$this->id);
		if(!$remaining) DigiplayDB::delete("keywords", "id = ".$this->id);
		return $result;
	}
}
?>