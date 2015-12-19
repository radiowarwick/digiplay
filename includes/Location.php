<?php
Class Location {
	protected $id;
	protected $key;

	public function get_id() { return $this->id; }
	public function get_key() { return $this->get_config("security_key")->get_val(); }

	public function get_config($param) { return Configs::get(NULL,$this,$param); }	
}
?>