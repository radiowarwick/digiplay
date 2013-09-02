<?php
Class Location {
	protected $id;
	protected $key;

	public function get_id() { return $this->id; }
	public function get_key() { return Configs::get(NULL,$this,"security_key")->get_val(); }
	
}
?>