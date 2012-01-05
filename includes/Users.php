<?php
class Users{
	public static function get($username){
		return self::get_by_username($username);
	}
	
	public function get_by_username($username) {
		$result = DigiplayDB::query("SELECT * FROM users WHERE username = '".$username."';");
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"User");
		} else return false;
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM users WHERE id = ".$id);
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"User");
		} else return false;
	}

}	
?>
