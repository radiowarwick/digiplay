<?php
class Configs {
	public function get($id = NULL, $location = NULL, $parameter = NULL, $val = NULL) { 
		if(isset($id)) return $this->get_by_id($id);

		$sql = "SELECT * FROM configuration WHERE id IS NOT NULL";
		if(isset($location)) $sql .= " AND location = ".(is_numeric($location)? $location : $location->get_id());
		if(isset($parameter)) $sql .= " AND parameter = '".$parameter."'";
		if(isset($val)) $sql .= " AND val = '".$val."'";

		$result = DigiplayDB::query($sql);

		if(pg_num_rows($result) > 1) {
			$configs = array();
			while($config = pg_fetch_object($result,NULL,"Config")) $configs[] = $config;
			return $configs;
		} else {
			return pg_fetch_object($result,NULL,"Config");
		}
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM configuration WHERE id = ".$id.";");
		return pg_fetch_object($result, NULL, "Config");
	}

	public function get_by_location($location) {
		$configs = array();
		$result = DigiplayDB::query("SELECT * FROM configuration WHERE location = ".$location->get_id().";");
		while($config = pg_fetch_object($result,NULL,"Config")) $configs[] = $config;
    	return $configs;
	}

	public function get_system_param($parameter) {
		return self::get(NULL,-1,$parameter)->get_val();
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