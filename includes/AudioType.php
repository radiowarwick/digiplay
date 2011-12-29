<?php
class AudioType {
	protected $id;
	protected $name;
	protected $description;

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_description() {
		return $this->description;
	}
}
?>