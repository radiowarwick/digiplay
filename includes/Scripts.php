<?php

class Scripts {
	public static function get_by_id($id) { return DigiplayDB::select("* FROM scripts WHERE id = ".$id, "Script"); }
}

?>