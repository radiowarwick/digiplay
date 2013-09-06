<?php

class ShowplanItems {
	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM showitems WHERE id = ".$id);
		return pg_fetch_object($result, NULL, "ShowplanItem");
	}

	public function get_by_showplan($showplan) {
		$result = DigiplayDB::query("SELECT * FROM showitems WHERE showplanid = ".$showplan->get_id()." ORDER BY position ASC;");
		$showplanitems = array();
		while($object = pg_fetch_object($result,NULL,"ShowplanItem")) $showplanitems[] = $object;
		return $showplanitems;
	}
}

?>