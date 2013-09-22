<?php
Class Config {
	protected $id;
	protected $location;
	protected $parameter;
	protected $val;

	public function get_id() { return $this->id; }
	public function get_location() { return Locations::get_by_id($this->location); }
	public function get_parameter() { return $this->parameter; }
	public function get_val() { return $this->val; }

	public function set_location($location) { 
		$result = DigiplayDB::update("configuration", array("location" => $location->get_id(), "id = ".$this->id));
		if($result) return ($this->location = $location->get_id());
	}

	public function set_parameter($parameter) {
		$result = DigiplayDB::update("configuration", array("parameter" => $parameter, "id = ".$this->id));
		if($result) return ($this->parameter = $parameter);
	}

	public function set_val($val) {
		$result = DigiplayDB::update("configuration", array("val" => $val, "id = ".$this->id));
		if($result) return ($this->val = $val);
	}

}
?>