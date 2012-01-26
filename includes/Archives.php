<?php
class Archives {
	public function get($id) {
		return self::get_by_id($id);
	}

	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM archives WHERE id = '".$id."'");
		if(pg_num_rows($result)) {
			return pg_fetch_object($result,NULL,"Archive");
		} else return false;
	}

	public function get_all() {
		$archives = array();
		$result = DigiplayDB::query("SELECT * FROM archives;");
		while($object = pg_fetch_object($result,NULL,"Archive"))
                 $archives[] = $object;
    	return ((count($archives) > 0)? $archives : false);
	}
}
?>