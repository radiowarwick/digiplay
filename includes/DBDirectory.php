<?php

class DBDirectory {
	protected $id;
	protected $parent;
	protected $name;
	protected $notes;
	protected $inherit;

	public function get_id() { return $this->id; }
	public function get_parent() { return Directories::get_by_id($this->parent); }
	public function get_name() { return $this->name; }
	public function get_notes() { return $this->notes; }
	public function get_inherit() { return (($this->inherit == "t")? TRUE : FALSE); }

	public function set_parent($parent) { $this->parent = $parent->get_id(); }
	public function set_name($name) { $this->name = $name; }
	public function set_notes($notes) { $this->notes = $notes; }
	public function set_inherit($inherit) { $this->inherit = $inherit; }

	public function save() {
		if(!$this->parent) return false;
		if($this->id) DigiplayDB::update("dir", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("dir", get_object_vars($this), "id");
		return $this->id;
	}

	public function get_children($levels = 1) {
		if($levels = 1)	return DBDirectories::get_by_parent($this);

	}

}

?>