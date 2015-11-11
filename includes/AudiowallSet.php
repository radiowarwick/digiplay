<?php
class AudiowallSet {
	protected $id;
	protected $name;
	protected $description;
	protected $walls = NULL;
	
	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_description() { return $this->description; }
	
	public function set_id($id){ $this->id = $id; }
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
		if (is_null($this->id)){
			$query = "INSERT INTO aw_sets (name, description) VALUES ('".$this->name."','".$this->description."') RETURNING id";
			$result = DigiplayDB::query($query);
			$this->set_id($result->fetchColumn(0));
		} else {
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

	public function get_users() {
		$query = "userid FROM usersconfigs WHERE configid = '1' AND val = '".$this->id."'";
		$result = DigiplayDB::select($query, NULL, true);
		return $result;
	}

	public function get_users_with_permissions() {
		$query = "user_id AS id FROM aw_sets_permissions WHERE set_id = '".$this->id."'";
		return DigiplayDB::select($query, "User");
	}

	public function get_user_permissions($userid) {
		$query = "permissions FROM aw_sets_permissions WHERE user_id = '".$userid."' AND set_id = '".$this->id."'";
		return DigiplayDB::select($query);
	}

	public function user_can_view() {
		if (Session::is_group_user('Audiowalls Admin')) return 1;
		$result = $this->get_user_permissions(Session::get_id());
		if (is_null($result) || $result[0] == '0') return 0;
		else return 1;
	}

	public function user_can_edit() {
		if (Session::is_group_user('Audiowalls Admin')) return 1;
		$result = $this->get_user_permissions(Session::get_id());
		if (is_null($result) || $result[1] == '0') return 0;
		else return 1;
	}

	public function user_can_delete() {
		if (Session::is_group_user('Audiowalls Admin')) return 1;
		$result = $this->get_user_permissions(Session::get_id());
		if (is_null($result) || $result[2] == '0') return 0;
		else return 1;
	}

}
?>