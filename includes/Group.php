<?php
class Group{
	private $name;
	private $description;
	private $id;
	private $parentid;

	public function get_id(){
		return $this->id;	
	}
	public function get_name(){
		return $this->name;
	}
	public function get_description(){
		return $this->description;
	}
	public function get_parentid(){
		return $this->parentid;
	}
	public function get_name_pretty(){
		$return = $this->name;
		$return = str_replace("_"," ",$return);
		$return = ucwords($return);
		return $return;
	}
	public function is_user($user){
		if(is_null($user)) $user = Session::get_user();
		$parent = Groups::get($this->get_parentid());
		$result = DigiplayDB::query("SELECT * FROM usersgroups WHERE (groupid = ".$this->get_id().") AND userid = '".$user->get_id()."'");
		if(pg_num_rows($result) > 0)
			return true;
		else 
			if($parent)	return $parent->is_user($user);
		return false;
	}

	public function get_users(){
		$result = DigiplayDB::query("SELECT users.* FROM usersgroups INNER JOIN users USING users.id ON usersgroups.userid WHERE usersgroups.groupid = '".$this->groupid."'");
		$array = array();
		while($object = pg_fetch_object($result, NULL, 'User'))
			$array[] = $object;
		return $array;
	}

	public function add_user($user_object){
		if(!(Session::is_group_user("Group Admin")))	throw new UserError("You are not a groups administrator");
		DigiplayDB::query("INSERT INTO usersgroups (username,groupid) VALUES
		('".$user_object->get_id()."','".$this->get_id()."')");
	}
	public function remove_user($user_object){
		if(!(Session::is_group_user("Group Admin")))	throw new UserError("You are not a groups administrator");
		if($user_object == Session::get_profile())	throw new UserError("You cannot remove yourself from a group");
		return DigiplayDB::query("DELETE FROM usersgroups WHERE
		userid = '".$user_object->get_id()."' AND groupid = '".$this->get_id()."'");
	}

	public function save(){
		if(is_null($this->groupid))
			DigiplayDB::query("INSERT INTO groups (name,description,parentid) VALUES ('".$this->get_name()."','".$this->get_description()."',".$this->get_parentid().")");
	}
	public function delete(){
			DigiplayDB::query("DELETE FROM groups WHERE id = '".$this->get_id()."'");
	}
}
?>
