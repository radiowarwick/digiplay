<?php
Class Status {
	protected $id;
	protected $name;
	protected $status;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_status() { return $this->status; }

	public function set_name($name) { $this->name = $name; }
	public function set_status($status) { $this->status = $status; }

	public function save() {
		if(!$this->status) return false;
		if($this->id) DigiplayDB::update("info_status", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("info_status", get_object_vars($this), "id");
		return $this->id;
	}

	public function get_real_status() {
		if ($this->status == 0) return "success";
		if ($this->status == 1) return "warning";
		if ($this->status == 2) return "danger";
		return "default";
	}

	public function delete() { return DigiplayDB::delete("info_status", "id = ".$this->id); }

}
?>
