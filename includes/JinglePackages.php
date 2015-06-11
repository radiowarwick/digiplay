<?php

class JinglePackages {

	public static function get_all($include_default = true) {
		return DigiplayDB::select('* FROM jinglepkgs'.($include_default? '' : ' WHERE id != 1').' ORDER BY id ASC', 'JinglePackage', true);
	}

	public static function get_by_id($id) {
		return DigiplayDB::select('* FROM jinglepkgs WHERE id = '.$id, 'JinglePackage', false);
	}

}

?>
