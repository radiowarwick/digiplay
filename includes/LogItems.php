<?php
Class LogItems {
	public function get($location = NULL,$sort = NULL, $limit = NULL, $offset = NULL) {
		$sql = "* FROM log";

		if(!is_null($location)) $sql .= " WHERE location = ".(is_numeric($location)? $location : $location->get_id());
		if(!is_null($sort)) $sql .= " ORDER BY ".$sort;
		if ( !is_null($limit) && is_numeric($limit) ) {
			$sql .= " LIMIT ". $limit;
			if ( !is_null($offset) && is_numeric($offset) ) $sql .= " OFFSET ". $offset;
		}
		
		return DigiplayDB::select($sql, "LogItem");
	}
	
	public function get_by_id($id) { return DigiplayDB::select("* FROM log WHERE id = ".$id); }
}