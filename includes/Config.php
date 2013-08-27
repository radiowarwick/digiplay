<?php
class Config {
	public function get_param($parameter,$location = -1) {
		$query = DigiplayDB::query("SELECT val FROM configuration WHERE parameter = '$parameter' AND location = $location;");
		if(pg_num_rows($query) == 1) {
			return pg_fetch_result($query,0);
		} else {
			return false;
		}
	}

	public function set_param($parameter,$value,$location = -1) {
		$query = DigiplayDB::query("SELECT * FROM configuration WHERE parameter = $parameter;");
		if(pg_num_rows($query) == 1) {
			$result = pg_fetch_assoc($query);
			DigiplayDB::query("UPDATE configuration SET val = '$value' WHERE parameter = '$parameter'");
			return true;
		} else {
			DigiplayDB::query("INSERT INTO configuration (parameter,val,location) VALUES ('$parameter','$value',$location);");
			return true;
		}
	}

	public function get_locations() {
		$locations = array();
		$result = DigiplayDB::query("SELECT location FROM configuration WHERE location != -1 GROUP BY location ORDER BY location ASC;");
		while($location = pg_fetch_assoc($result, NULL)) {
			 $locations[] = $location['location'];
		}
    	return $locations;
	}

	public function get_by_location($location) {
		$settings = array();
		$result = DigiplayDB::query("SELECT * FROM configuration WHERE location = '$location';");
		while($setting = pg_fetch_assoc($result,NULL))
                 $settings[] = $setting['parameter'];
    	return $settings;
	}

	public function get_location_from_key($key) {
		$query = DigiplayDB::query("SELECT location FROM configuration WHERE parameter = 'security_key' AND val = '".$key."';");
		if(pg_num_rows($query) == 1) {
			return pg_fetch_result($query,0);
		} else {
			return false;
		}
	}

	public function key_generator() {
		$fp = @fopen('/dev/urandom','rb');
		if ($fp !== FALSE) {
    		$pr_bits .= @fread($fp,16);
    		@fclose($fp);
    	}
    	return sha1($pr_bits);
	}
}
?>