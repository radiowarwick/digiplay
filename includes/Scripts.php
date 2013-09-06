<?php

class Scripts {
	public function get_by_id($id) {
		$result = DigiplayDB::query("SELECT * FROM scripts WHERE id = ".$id);
		return pg_fetch_object($result, NULL, "Script");
	}
}

?>