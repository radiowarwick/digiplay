<?php
class User{
	protected $username;
	protected $id;

	/* Standard functions */
	
	public function get_username() { return $this->username; }
	public function get_id() { return $this->id; }

	public function get_ldap_attributes() {
		$ldap = new LDAP;
		return $ldap->userdetails($this->username);
	}

	public function get_groups() { return Groups::get_by_user($this); }

	public function get_config_var($param) { return DigiplayDB::select("val FROM usersconfigs INNER JOIN configs ON configs.id = usersconfigs.configid WHERE userid = ".$this->id." AND configs.name = '".$param."'"); }
}
?>
