<?php 
class SustainerSlots{

	public static function get($id) {
		return self::get_by_id($id);
	}

	public static function get_by_id($id) {
		return DigiplayDB::select("* FROM sustslots WHERE id = :id", "SustainerSlot", false, array(':id' => $id));
	}

	public static function get_all() {
		return DigiplayDB::select("* FROM sustslots ORDER BY time ASC, day ASC", "SustainerSlot");
		//return DigiplayDB::select("* FROM sustslots ORDER BY time ASC, case when day = 'm' then 1 when day = 'tu' then 2 when day = 'w' then 3 when day = 'th' then 4 when day = 'f' then 5 when day = 'sa' then 6 when day = 'su' then 7 end", "SustainerSlot");
	}

	public static function get_by_playlist($playlist) {
		return DigiplayDB::select("* FROM sustslots WHERE playlistid = :playlist", "SustainerSlot", false, array(':playlist' => $playlist));
	}
}
?>