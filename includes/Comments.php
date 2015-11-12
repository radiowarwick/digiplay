<?php
class Comments {

	public static function get_by_id($id) { return DigiplayDB::select("* FROM info_fault_comments WHERE id = :id", "Comment", false, $id); }

	public static function get_by_fault($id) { return DigiplayDB::select("* FROM info_fault_comments WHERE faultid = :id ORDER BY postdate DESC", "Comment", true, $id); }

	public static function get_fault_comments($id) { return DigiplayDB::select("COUNT(*) FROM info_fault_comments WHERE faultid = :id", NULL, false, $id); }

	public static function get_total_comments() { return DigiplayDB::select("COUNT(*) FROM info_fault_comments"); }

}
?>
