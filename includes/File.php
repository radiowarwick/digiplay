<?php

class File {
	protected $itemtype;
	protected $id;
	protected $name;
	protected $parent;
	protected $userid;
	protected $username;
	protected $permissions;

	public function get_itemtype() { return $this->itemtype; }
	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_parent() { return Files::get_by_id($this->parent); }
	public function get_user() { return Users::get_by_id($this->userid); }
	public function get_permissions() { return $permissions; }

	public function get_children() { return Files::get_by_parent($this); }

	public function has_children() { 
		if($this->itemtype != "dir") return false;

		// Only check for proper permissions if something exists anyway = more speed
		//if(DigiplayDB::select("SELECT true FROM dir WHERE parent = :parent", NULL, false, $this->id))
		return DigiplayDB::select("true FROM v_tree WHERE parent = :parent AND userid = :userid", NULL, false, array(":parent" => $this->id, ":userid" => Session::get_user()->get_id()));
		//else return false;
	}

	public function count() {
		return DigiplayDB::select("count(*) from v_tree where parent = :parent AND userid = :userid", null, false, array("parent" => $this->id, ":userid" => Session::get_user()->get_id()));
	}
}

?>