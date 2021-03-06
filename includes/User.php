<?php
class User{
	protected $username;
	protected $id;
	protected $enabled;
	protected $ghost;

	/* Standard functions */
	
	public function get_username() { return $this->username; }
	public function get_id() { return $this->id; }
	public function is_enabled() { return $this->enabled; }
	public function is_ghost() { return $this->ghost; }

	public function set_enabled($enabled) { $this->enabled = (bool) $enabled; }
	public function set_ghost($ghost) { $this->ghost = (bool) $ghost; }

	public function save() { 
		if(!$this->enabled) $this->enabled = TRUE;
		if(!$this->ghost) $this->ghost = FALSE;
		return DigiplayDB::update("users", get_object_vars($this), "id = ".$this->id); 
	}

	public function get_ldap_attributes() {
		$ldap = new LDAP;
		return $ldap->attributes($this->username);
	}

	public function get_display_name() {
		$user = $this->get_ldap_attributes();
		return $user['displayName'][0];
	}

	public function get_groups() { return Groups::get_by_user($this); }

	public function get_config_var($param) { return DigiplayDB::select("val FROM usersconfigs INNER JOIN configs ON configs.id = usersconfigs.configid WHERE userid = ".$this->id." AND configs.name = '".$param."'"); }

	public function get_user_folder() { 
		$users_folder = Files::get_by_id(7, 'dir');
		return Files::find_in_dir($users_folder, $this->username);
	}
}
?>
