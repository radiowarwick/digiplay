<?php
Class Locations {
	public static function get_by_id($id) { return DigiplayDB::select("location AS id, val AS key FROM configuration WHERE location = ".$id." AND parameter = 'security_key';", "Location"); }
	public static function get_all() { return DigiplayDB::select("location AS id, val AS key FROM configuration WHERE location != -1 AND parameter = 'security_key' ORDER BY location ASC;", "Location"); }
	
	public static function get_by_key($key) {
		$config = Configs::get(NULL,NULL,"security_key",$key);
		if(!is_array($config)) return $config->get_location();
		else return false;
	}
}