<?php
Class LogItems {
	public function get($location = NULL,$sort = NULL, $limit = NULL, $offset = NULL) {
		$sql = "SELECT * FROM log";

		if(!is_null($location)) $sql .= " WHERE location = ".(is_numeric($location)? $location : $location->get_id());
		if(!is_null($sort)) $sql .= " ORDER BY ".$sort;
		if ( !is_null($limit) && is_numeric($limit) ) {
			$sql .= " LIMIT ". $limit;
			if ( !is_null($offset) && is_numeric($offset) ) $sql .= " OFFSET ". $offset;
		}
		
		$result = DigiplayDB::query($sql);
		$return = array();
		while ($logitem = pg_fetch_object($result, NULL, 'LogItem')) {
			$return[] = $logitem;
		}

		return $return;
	}
	
	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM log WHERE id = ".$id);
		if(pg_num_rows($result)) return pg_fetch_object($result,NULL,"LogItem");
		else return false;
	}
}