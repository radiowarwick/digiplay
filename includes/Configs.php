<?php
class Configs {
	public static function get($id = NULL, $location = NULL, $parameter = NULL, $val = NULL) { 
		if(isset($id)) return $this->get_by_id($id);

		$sql = "* FROM configuration WHERE id IS NOT NULL";
		if(isset($location)) $sql .= " AND location = ".(is_numeric($location)? $location : $location->get_id());
		if(isset($parameter)) $sql .= " AND parameter = '".$parameter."'";
		if(isset($val)) $sql .= " AND val = '".$val."'";

		return DigiplayDB::select($sql,"Config");
	}

	public static function get_by_id($id) { return DigiplayDB::select("* FROM configuration WHERE id = ".$id, "Config"); }
	public static function get_by_location($location) { return DigiplayDB::select("* FROM configuration WHERE location = ".$location->get_id(), "Config", true); }

	public static function get_system_param($parameter) { return self::get(NULL,-1,$parameter)->get_val(); }

	public static function key_generator() {
		$fp = @fopen('/dev/urandom','rb');
		if ($fp !== FALSE) {
    		$pr_bits .= @fread($fp,16);
    		@fclose($fp);
    	}
    	return sha1($pr_bits);
	}
}
?>