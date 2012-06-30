<?php
class Session{
	private static $instance;
	private static $data = array('user' => false);
	private static $groups;
	private static $user_object;
	
	public static function is_user(){
		return self::$data['user'];
	}
	public static function is_developer(){
		return self::is_group_user('Developers');
	}
	public static function is_firstlogin(){
		return self::$data['lastlogin']==0;
	}
	public static function is_group_user($group){
		$group = Groups::get_by_name($group);
		if($group) return (Session::is_user() ? $group->is_user(self::get_user()) : false);
		else return false;
	}
	public static function is_admin(){
		return (self::is_group_user('Administrators') || self::is_developer());
	}
	public static function get_username(){		return self::$data['username'];		}
	public static function get_id(){		return self::$data['id'];		}
	public static function get_name(){		return self::$data['first_name'].' '.self::$data['surname'];		}
	public static function get_first_name() {	return self::$data['first_name'];	}
	public static function get_surname() {	return self::$data['surname'];	}
	public static function get_nick(){		return self::$data['nick'];		}
	public static function get_ou(){		return self::$data['ou'];		}
	public static function get_lastlogin(){		return self::$data['lastlogin'];	}

	public static function get_groups(){
		if(!self::$data['user']) return array();
		if(!isset(self::$groups)){
			$result = DigiplayDB::query("SELECT groups.*,usersgroups.* FROM groups INNER JOIN usersgroups USING usersgroups.groupid ON groups.id WHERE usersgroups.userid = '".self::$data['id']."' ORDER BY groups.id");
			self::$groups = array();
			if(pg_num_rows($result)>0){
				while($object = pg_fetch_object($result,null,"Group"))
				self::$groups[] = $object;
			}
		}
		return self::$groups;
	}
	public static function get_group_names(){
		if(!isset(self::$groupNames)){
			if (!isset(self::$groups))
				self::get_groups();
			if(count(self::$groups) > 0){
				foreach (self::$groups as $group)
					self::$groupNames[] = $group->get_name();
			}
		}
		return self::$groupNames;
	}
    public static function get_user(){
		if(is_null(self::$user_object))
			self::$user_object = Users::get(self::$data['username']);
		return self::$user_object;
	}

	public static function login($username,$password){
		if(Config::get_param("auth_method") != "LDAP") {
			$local_user = pg_fetch_assoc(DigiplayDB::query("SELECT * FROM users WHERE username = '".$username."' AND password = '".md5($password)."';"));
			if($local_user) {
				self::$data["user"] = true;
				self::$data["id"] = $local_user["id"];
			} else return false;
		} else {
			$ldap_instance = new LDAP;
			if(!$ldap_instance->login($username, $password)) return false;
			if(is_object($ldap_instance) && get_class($ldap_instance) == "LDAP"){
				if($ldap_instance->login_status()){
					self::$data = $ldap_instance->userdetails();
					self::$data['user'] = true;
					$id = pg_fetch_array(DigiplayDB::query("SELECT id FROM users WHERE username = '".self::$data['username']."';"));
					self::$data['id'] = $id[0];
				} else return false;
			}
		}
		if(self::$data["id"]) {
			$result = DigiplayDB::query("SELECT val FROM usersconfigs WHERE userid = ".self::$data['id']." AND configid = 3;");
			if(pg_num_rows($result)==1){
				self::$data['lastlogin'] = pg_fetch_result($result,0);
				DigiplayDB::query("UPDATE usersconfigs SET val = '".time()."' WHERE userid = ".self::$data['id']." AND configid = 3;");
			}else{
				DigiplayDB::query("INSERT INTO usersconfigs (userid,configid,val) VALUES (".self::$data['id'].",3,'".time()."');");
			}
			return true;
		} else {
		return false;
		}
	}
	public static function logout(){
		self::$data['user'] = false;
	}
    public static function start(){
		if(isset($_SESSION['user']))
			self::$data = unserialize($_SESSION['user']);
        self::$instance = new Session;
    }
    public static function stop(){
		if(self::$data['user'])	$_SESSION['user'] = serialize(self::$data);
		else						unset($_SESSION['user']);
    }
    
	function __destruct(){
		self::stop();
	}
}

Session::start();
?>
