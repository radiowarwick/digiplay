<?php

class Files {

	public static function get_by_id($id, $itemtype = 'dir') { return DigiplayDB::select("* FROM v_tree WHERE itemtype = :itemtype AND id = :id AND username = :username", "File", false, array(":itemtype" => $itemtype, ":id" => $id, ":username" => Session::get_user()->get_username())); }
	public static function get_by_parent($parent) { return DigiplayDB::select("* FROM v_tree WHERE parent = :parent AND username = :username", "File", true, array(":parent" => $parent->get_id(), ":username" => Session::get_user()->get_username())); }

}

?>