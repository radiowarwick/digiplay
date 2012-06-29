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
}
?>