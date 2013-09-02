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
		$result = DigiplayDB::query("UPDATE configuration SET location = '".$location->get_id()."' WHERE id = ".$this->id);
		if((bool)$result) return ($this->location = $location->get_id());			
	}

	public function set_parameter($parameter) {
		$result = DigiplayDB::query("UPDATE configuration SET parameter = '".$parameter."' WHERE id = ".$this->id);
		if((bool)$result) return ($this->parameter = $parameter);			
	}

	public function set_val($val) {
		$result = DigiplayDB::query("UPDATE configuration SET val = '".$val."' WHERE id = ".$this->id);
		if((bool)$result) return ($this->val = $val);			
	}

}
?>