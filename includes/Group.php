<?php
class Group{
	private $group;
	private $group_id;
	private $parent_group_id;

	public function get_id(){
		return $this->group_id;	
	}
	public function get_name(){
		return $this->group;
	}
	public function get_parent_group_id(){
		return $this->parent_group_id;
	}
	public function get_name_pretty(){
		$return = $this->group;
		$return = str_replace("_"," ",$return);
		$return = ucwords($return);
		return $return;
	}
	public function is_user($user){
		if(is_null($user)) $user = Session::get_user();
		$parent = Groups::get($this->parent_group_id);
		if(!$parent) $parent = 0;
		else $parent = $parent->get_id();
		$result = DigiplayDB::query("SELECT * FROM web_users_groups WHERE (group_id = ".$this->group_id." OR group_id = ".$parent.") AND username = '".$user->get_username()."'");
		if(pg_num_rows($result) > 0)
			return true;
		return false;
	}

	public function get_users($admin = true){
		$result = DigiplayDB::query("SELECT web_users.* FROM web_users_groups INNER JOIN web_users USING (username) WHERE groupid = '".$this->groupid."'".($admin?"":"AND admin = false"));
		$array = array();
		while($object = pg_fetch_object($result, NULL, 'User'))
			$array[] = $object;
		return $array;
	}

	public function add_user($user_object,$admin = false){
		if(!($this->admin || Session::is_group_user("group_admin")))	throw new UserError("You are not an administrator of this group");
		DigiplayDB::query("INSERT INTO web_users_groups (username,groupid,admin) VALUES
		('".$user_object->get_username()."','".$this->get_groupid()."','".($admin?'true':'false')."')");
	}
	public function remove_user($user_object){
		if(!($this->admin || Session::is_group_user("group_admin")))	throw new UserError("You are not an administrator of this group");
		if($user_object == Session::get_profile())	throw new UserError("You cannot remove yourself from a group");
		return DigiplayDB::query("DELETE FROM web_users_groups WHERE
		username = '".$user_object->get_username()."' AND groupid = '".$this->get_groupid()."'");
	}

	public function save(){
		if(is_null($this->groupid))
			DigiplayDB::query("INSERT INTO web_groups (group) VALUES ('".$this->get_group()."')");
	}
	public function delete(){
			DigiplayDB::query("DELETE FROM web_groups WHERE groupid = '".$this->get_groupid()."'");
	}
}
?>
