<?php
Class Fault {
	protected $id;
	protected $author;
	protected $status;
	protected $assignedto;
	protected $content;
	protected $postdate;

	public function get_id() { return $this->id; }
	public function get_author() { return $this->author; }
	public function get_status() { return $this->status; }
	public function get_assignedto() { return $this->assignedto; }
	public function get_content() { return $this->content; }
	public function get_postdate() { return date('jS F Y, g:ia', $this->postdate); }

	public function set_author($author) { $this->author = $author; }
	public function set_content($content) { $this->content = $content; }
	public function set_status($status) { $this->status = $status; }
	public function set_postdate($postdate) { $this->postdate = $postdate; }

	public function save() {
		if(!$this->content) return false;
		if($this->id) DigiplayDB::update("info_faults", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("info_faults", get_object_vars($this), "id");
		return $this->id;
	}

	public function get_real_status() {
		if ($this->status == 1) return "Not yet read";
		if ($this->status == 2) return "On hold";
		if ($this->status == 3) return "Work in progress";
		if ($this->status == 4) return "Fault complete";
		return "NULL";
	}

	public function get_panel_class() {
		if ($this->status == 1) return "default";
		if ($this->status == 2) return "danger";
		if ($this->status == 3) return "warning";
		if ($this->status == 4) return "success";
		return "default";
	}

	/*public function set_location($location) { 
		$result = DigiplayDB::update("configuration", array("location" => $location->get_id()), "id = ".$this->id);
		if($result) return ($this->location = $location->get_id());
	}

	public function set_parameter($parameter) {
		$result = DigiplayDB::update("configuration", array("parameter" => $parameter), "id = ".$this->id);
		if($result) return ($this->parameter = $parameter);
	}

	public function set_val($val) {
		$result = DigiplayDB::update("configuration", array("val" => $val), "id = ".$this->id);
		if($result) return ($this->val = $val);
	}*/

}
?>
