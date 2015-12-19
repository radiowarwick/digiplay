<?php

class Script {
	protected $id;
	protected $name;
	protected $contents;
	protected $length;
	protected $userid;
	protected $creationdate;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_contents() { return $this->contents; }
	public function get_length() { return $this->length; }
	public function get_user() { return Users::get_by_id($this->userid); }
	public function get_creationdate() { return $this->creationdate; }

	public function set_name($name) { $this->name = $name; }
	public function set_contents($contents) { $this->contents = $contents; }
	public function set_length($length) { $this->length = $length; }
	public function set_user($user) { $this->userid = $user->get_id(); }
	public function set_creationdate($date) { $this->creationdate = $date; }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::update("scripts", get_object_vars($this), "id = ".$this->id.";");
		else {
			if(!$this->creationdate) $this->creationdate = time();
			$this->id = DigiplayDB::insert("scripts", get_object_vars($this), "id");
		}
		return $this->id;
	}
}

?>