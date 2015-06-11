<?php

class JinglePackage {

	private $id;
	private $name;
	private $description;
	private $enabled;
	private $archived;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_description() { return $this->description; }
	public function get_enabled() { return ($this->enabled == 't'); }
	public function get_archived() { return ($this->archived == 't'); }

	public function set_name($name) { $this->name = $name; }
	public function set_description($description) { $this->description = $description; }
	public function set_enabled($enabled) { $this->enabled = $enabled? 't':'f'; }
	public function set_archived($archived) { $this->archived = $archived? 't':'f'; }

	public function get_jingles() {
		return DigiplayDB::select('audio.* FROM audio INNER JOIN audiojinglepkgs ON audio.id = audiojinglepkgs.audioid WHERE audiojinglepkgs.jinglepkgid ='.$this->id, 'Jingle', true);
	}

	public function count_jingles() {
		return (int)DigiplayDB::select('COUNT(id) AS Jingles FROM audiojinglepkgs WHERE jinglepkgid = '.$this->id);
	}

	public function save() {
		if(isset($this->id)) DigiplayDB::update('jinglepkgs', get_object_vars($this), 'id = '.$this->id);
		else {
			if(!isset($this->name)) return false;
			if(!isset($this->enabled)) $this->enabled = 'f';
			if(!isset($this->archived)) $this->archived = 'f';
			$this->id = DigiplayDB::insert('jinglepkgs', get_object_vars($this), 'id');
		}
		return $this->id;
	}

	public function delete() {
		foreach($this->get_jingles() as $jingle) $this->delete_jingle($jingle);
		return DigiplayDB::delete('jinglepkgs', 'id = \''.$this->id.'\'');
	}

	public function delete_jingle($jingle) {
		return DigiplayDB::delete('audiojinglepkgs', 'audioid = \''.$jingle->get_id().'\' AND jinglepkgid = \''.$this->id.'\'');
	}
}

?>
