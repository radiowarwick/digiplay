<?php
class Archive {
	protected $id;
	protected $name;
	protected $localpath;
	protected $remotepath;
	
	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_localpath() {
		return $this->localpath;
	}
	
	public function get_remotepath() {
		return $this->remotepath;
	}
}
?>