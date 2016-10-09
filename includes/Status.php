<?php
Class Status {
	protected $id;
	protected $name;
	protected $description;
	protected $status;
	protected $order;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_description() { return $this->description; }
	public function get_status() { return $this->status; }
	public function get_order() { return $this->order; }

	public function set_name($name) { $this->name = $name; }
	public function set_description($description) { $this->description = $description; }
	public function set_status($status) { $this->status = $status; }
	public function set_order($order) { $this->order = $order; }
	
	public function save() {
		if(!$this->status) return false;
		if($this->id) DigiplayDB::update("info_status", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("info_status", get_object_vars($this), "id");
		return $this->id;
	}

	public function get_status_info() {
		$thisStatus = DigiplayDB::select("* FROM info_status_statuses WHERE id = ".$this->status, "StatusInfo");
		$statusInfo = array();
		$statusInfo['status'] = $thisStatus->get_status();
		$statusInfo['colour'] = $thisStatus->get_colour();
		return $statusInfo;
	}

	public function delete() { return DigiplayDB::delete("info_status", "id = ".$this->id); }

}
?>
