<?php
class Users{
	public static function search_username($username){
                if(!preg_match("/[a-z0-9]+/",$username)){
                        throw new UserError("Invalid Username");
                        return false;
                }
		$result = DigiplayDB::query("SELECT * FROM users WHERE username = '".pg_escape_string($username)."' LIMIT 1;");
		if(pg_num_rows($result))
			return pg_fetch_object($result,null,"User");
		else
			return false;
	}
	public static function check_exists($username){
                if(!preg_match("/[a-z0-9]+/",$username)){
                        throw new UserError("Invalid Username");
                        return false;
                }
		return pg_fetch_result(DigiplayDB::query("SELECT COUNT(1) FROM web_users WHERE username = '".pg_escape_string($username)."' LIMIT 1;"),0,0);
	}
	public static function get($username){
		return self::search_username($username);
	}

}	
?>
