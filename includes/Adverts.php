<?php
class Adverts {

	public static function get($id) { return self::get_by_id($id); }

	public static function get_by_id($id) { return DigiplayDB::select("* FROM audio WHERE id = ".$id, "Track"); }
	public static function get_by_md5($md5) { return DigiplayDB::select("* FROM audio WHERE md5 = '".$md5."'", "Track"); }

	public static function get_all() { return DigiplayDB::select("* FROM audio WHERE type = ".AudioTypes::get("Advert")->get_id(), "Advert"); }

	public static function get_total_adverts() { return DigiplayDB::select("COUNT(id) FROM audio WHERE type = ".AudioTypes::get("Advert")->get_id()); }
	public static function get_total_length() { return DigiplayDB::select("SUM(length_smpl) FROM audio WHERE type = ".AudioTypes::get("Advert")->get_id()) / 44100; }

	public static function get_newest($num=10) { return DigiplayDB::select("* FROM audio WHERE type = ".AudioTypes::get("Jingle")->get_id()." ORDER BY id DESC LIMIT ".$num.";", "Track", true); }
}
?>
