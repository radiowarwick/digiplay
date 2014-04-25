<?php
class Session{
	private static $instance;
	private static $data = array("user" => false);
	private static $groups;
	private static $user_object;
	
	public static function is_user() { return self::$data["user"]; }
	public static function is_admin() {	return (self::is_group_user("Administrators") || self::is_developer());	}
	public static function is_developer() { return self::is_group_user("Developers"); }
	public static function is_firstlogin() { return self::$data["lastlogin"]==0; }

	public static function is_group_user($group_name) {
		foreach(self::get_groups() as $group) if($group->get_name() == $group_name) return true;
		$group = Groups::get_by_name($group_name);
		if($group) return (Session::is_user() ? $group->is_user(self::get_user()) : false);
		else return false;
	}

	public static function get_username() { return self::get_user()->get_username(); }
	public static function get_id() { return self::get_user()->get_id(); }
	public static function get_name() { return self::$data["first_name"]." ".self::$data["surname"]; }
	public static function get_first_name() { return self::$data["first_name"]; }
	public static function get_surname() { return self::$data["surname"]; }
	public static function get_nick() {	return self::$data["nick"];	}
	public static function get_ou() { return self::$data["ou"]; }
	public static function get_lastlogin() { return self::$data['lastlogin']; }

	public static function get_groups() {
		if(!self::$data["user"]) return array();
		if(!isset(self::$groups)) self::$groups = self::get_user()->get_groups();
		return self::$groups;
	}

    public static function get_user() {
		if(is_null(self::$user_object))	self::$user_object = Users::get(self::$data["username"]);
		return self::$user_object;
	}

	public static function login($username,$password) {
		if(Configs::get_system_param("auth_method") != "LDAP") {
			$local_user = DigiplayDB::select("* FROM users WHERE username = '".$username."' AND password = '".md5($password)."';", "User");
			if($local_user) {
				self::$data["user"] = true;
				self::$user_object = $local_user;
			} else return false;
		} else {
			$ldap_instance = new LDAP;
			if(!$ldap_instance->login($username, $password)) return false;
			if(is_object($ldap_instance) && get_class($ldap_instance) == "LDAP"){
				if($ldap_instance->login_status()) {
					self::$data = $ldap_instance->userdetails();
					self::$data["user"] = true;

					# Get the user's info, or insert them as a new user if there isn't any
					self::$user_object = Users::get_by_username(self::$data["username"]);
					if(!self::$user_object) {
						$id = DigiplayDB::insert("users", array("username" => self::$data["username"], "password" => NULL, "enabled" => TRUE, "ghost" => FALSE), "id");
						self::$user_object = Users::get_by_id($id);
					}
				} else return false;
			}
		}
		if(self::$user_object) {
			$result = self::$user_object->get_config_var("user_curlogin");
			if($result) {
				self::$data["lastlogin"] = $result;
				DigiplayDB::query("UPDATE usersconfigs SET val = '".time()."' WHERE userid = ".self::$user_object->get_id()." AND configid = 3;");
			}else{
				DigiplayDB::query("INSERT INTO usersconfigs (userid,configid,val) VALUES (".self::$user_object->get_id().",3,'".time()."');");
			}
			return true;
		} else {
			return false;
		}
	}
	public static function logout() {
		self::$data["user"] = false;
	}
    public static function start() {
		if(isset($_SESSION["user"])) self::$data = unserialize($_SESSION["user"]);
        self::$instance = new Session;
    }
    public static function stop() {
		if(self::$data["user"])	$_SESSION["user"] = serialize(self::$data);
		else unset($_SESSION["user"]);
    }
    
	function __destruct() {	self::stop(); }
}

Session::start();
?>
