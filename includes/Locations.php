<?php
Class Locations {
	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT location AS id, val AS key FROM configuration WHERE location = ".$id." AND parameter = 'security_key';");
		return pg_fetch_object($result,NULL,"Location");	
	}

	public function get_all() {
		$locations = array();
		$result = DigiplayDB::query("SELECT location AS id, val AS key FROM configuration WHERE location != -1 AND parameter = 'security_key' ORDER BY location ASC;");
		while($location = pg_fetch_object($result, NULL, "Location")) $locations[] = $location;
    	return $locations;
	}

	public function get_by_key($key) {
		$config = Configs::get(NULL,NULL,"security_key",$key);
		if(!is_array($config)) return $config->get_location();
		else return false;
	}
}