<?php
class Prerecs {

	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM audio WHERE id = ".$id, "Prerec"); }
	public static function get_by_md5($md5) { return DigiplayDB::select("* FROM audio WHERE md5 = '".$md5."'", "Prerec"); }
	
}
?>
