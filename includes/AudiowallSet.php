<?php
class AudiowallSet {
	protected $id;
	protected $name;
	protected $description;
	protected $walls = NULL;
	
	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_description() { return $this->description; }
	
	public function set_name($name) { $this->name = $name; }
	public function set_description($description) { $this->description = $description; }
	
	/* Extended Functions */
	public function get_walls() {
		if ($this->walls == NULL) $this->walls = Audiowalls::get_by_set($this);
		return $this->walls;
	}
	
	public function empty_set() {
		$this->walls = array();
	}
	
	public function add_wall($audiowall) {
		$this->walls[] = $audiowall;
	}
	
	public function save() {
		$result = DigiplayDB::query("UPDATE aw_sets SET name='".$this->name."', description='".$this->description."' WHERE id=".$this->id."");
		$result = DigiplayDB::query("SELECT * FROM aw_walls WHERE set_id = ".$this->id."");
		while($object = pg_fetch_object($result,NULL,"Audiowall")){
			$object->delete();
		}
		foreach($this->walls as $wall){
			$wall->set_set_id($this->id);
			$wall->save();
		}
	}
}
?>