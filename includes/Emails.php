<?php
class Emails {
	public static function get($starttime = NULL, $endtime = NULL, $sort = NULL, $limit = NULL, $offset = NULL) {
		$sql = "* FROM email";
		
		if(!is_null($starttime) && !is_null($endtime) && is_numeric($starttime) && is_numeric($endtime)) 
            $sql .= " WHERE datetime BETWEEN ". $starttime ." AND ". $endtime;
		
		$sql .= " ORDER BY datetime ";
		if(!is_null($sort)) $sql .= $sort;
		else $sql .= "DESC";

		if(!is_null($limit) && is_numeric($limit)) {
			$sql .= " LIMIT ". $limit;
			if(!is_null($offset) && is_numeric($offset)) $sql .= " OFFSET ". $offset;
		}

		return DigiplayDB::select($sql, "Email");
	}
	
	public static function get_by_id($id) {	return DigiplayDB::select("* FROM email WHERE id = ". $id, "Email"); }
}
?>
	