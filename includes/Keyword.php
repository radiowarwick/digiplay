<?php
class Keyword {
	protected $id;
	protected $name;

	public function get_id() {
		return $this->id;
	}

	public function get_text() {
		return $this->name;
	}
}
?>