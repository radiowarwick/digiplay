<?php 
class AudiowallItems{

	public static function get($id) {
		return self::get_by_id($id);
	}

	public static function get_by_id($id) {
		return DigiplayDB::select("* FROM aw_items WHERE id = :id", "AudiowallItem", false, array(':id' => $id));
	}
	
	public static function get_by_wall($wall) {
		$return = array();
		foreach(DigiplayDB::select("* FROM aw_items WHERE wall_id = :wall_id ORDER BY item ASC;", "AudiowallItem", true, array(":wall_id" => $wall->get_id())) as $item) {
			$return[$item->get_item()] = $item;
		}
		return $return;
	}
}
?>