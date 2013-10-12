<?php
class Faults {

	public function get($id = NULL, $author = NULL, $assignedto = NULL) { 
		if(isset($id)) return self::get_by_id($id);

		$sql = "* FROM info_faults WHERE id IS NOT NULL";
		if(isset($author)) $sql .= " AND author = ".$author;
		if(isset($assignedto)) $sql .= " AND assignedto = '".$assignedto."'";
		$sql .= " ORDER BY postdate DESC";

		return DigiplayDB::select($sql,"Fault",true);
	}

	public function get_by_id($id) { return DigiplayDB::select("* FROM info_faults WHERE id = ".$id, "Fault"); }

	public function get_total_faults() { return DigiplayDB::select("COUNT(*) FROM info_faults"); }

	public function get_open_faults() { return DigiplayDB::select("COUNT(*) FROM info_faults WHERE status <> '4'"); }

	public function get_closed_faults() { return DigiplayDB::select("COUNT(*) FROM info_faults WHERE status = '4'"); }

}
?>
