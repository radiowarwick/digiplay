<?php
class Requests {
	public function get($id) {
		return self::get_by_id($id);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM requests WHERE id = ".$id);
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Request");
		} else return false;
	}

	public function get_by_user($user) {
		$requests = array();
		$result = DigiplayDB::query("SELECT * FROM requests WHERE userid = ".$user->get_id()); 
		while($object = pg_fetch_object($result,NULL,"Request"))
                 $requests[] = $object;
    	return ((count($requests) > 0)? $requests : false);
	}

	public function get_all() {
		$requests = array();
		$result = DigiplayDB::query("SELECT * FROM requests"); 
		while($object = pg_fetch_object($result,NULL,"Request"))
                 $requests[] = $object;
    	return ((count($requests) > 0)? $requests : false);
	}
}