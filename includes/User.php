<?php
class User{
	protected $username;
	protected $id;

	/* Standard functions */
	
	public function get_username(){
		return $this->username;
	}
	public function get_id(){
		return $this->id;
	}

	public function get_config_var($param){
		$result = DigiplayDB::query("SELECT val FROM usersconfigs INNER JOIN configs ON configs.id = usersconfigs.configid WHERE userid = ".$this->id);
		if(pg_num_rows($result)) return pg_fetch_result($result, NULL, 0);
		else return false;
	}
}
?>
