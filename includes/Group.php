<?php
class Group{
	private $name;
	private $description;
	private $id;
	private $parentid;

	public function get_id() { return $this->id;	}
	public function get_name() { return $this->name; }
	public function get_description() { return $this->description; }
	public function get_parentid() { return $this->parentid; }

	public function get_name_pretty() {
		$return = $this->name;
		$return = str_replace("_"," ",$return);
		$return = ucwords($return);
		return $return;
	}

	public function is_user($user) {
		if(is_null($user)) $user = Session::get_user();
		if($this->get_parentid()) $parent = Groups::get($this->get_parentid());
		$result = DigiplayDB::select("* FROM usersgroups WHERE (groupid = ".$this->get_id().") AND userid = '".$user->get_id()."'");
		if($result) return true;
		else if(isset($parent)) return $parent->is_user($user);
		return false;
	}

	public function get_users() { return DigiplayDB::select("users.* FROM users INNER JOIN usersgroups ON usersgroups.userid = users.id WHERE usersgroups.groupid = ".$this->id, "User", true); }
	public function get_children() { return Groups::get_by_parent($this); }

	public function add_user($user){ return DigiplayDB::insert("usersgroups", array("userid" => $user->get_id(), "groupid" => $this->id)); }
	public function remove_user($user) { return DigiplayDB::delete("usersgroups", "userid = '".$user->get_id()."' AND groupid = '".$this->id."'"); }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::update("groups", get_object_vars($this));
		else $this->id = DigiplayDB::insert("groups", get_object_vars($this), "id");
		return $this->id;
	}

	public function delete() { return DigiplayDB::delete("groups", "id = '".$this->id."'"); }
}
?>
