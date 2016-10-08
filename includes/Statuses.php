<?php
class Statuses {

	public static function get($id = NULL, $status = NULL) { 
		if(isset($id)) return self::get_by_id($id);

		$sql = "* FROM info_status WHERE id IS NOT NULL";
		if(isset($status)) $sql .= " AND status = ".$status;

		return DigiplayDB::select($sql,"Status",true);
	}

	public static function get_by_id($id) { return DigiplayDB::select("* FROM info_status WHERE id = ".$id, "Status"); }

}
?>
