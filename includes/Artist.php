<?php
class Artist {
	protected $id;
	protected $name;
	protected $alt_name;

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_alt_name() {
		return $this->alt_name;
	}
}
?>