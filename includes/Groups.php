<?php
class Groups{
	public static function all(){
		$result = DigiplayDB::query("SELECT * FROM web_groups ORDER BY web_groups.group");
		$array = array();
		while($object = pg_fetch_object($result,null,'Group'))
			$array[] = $object;
		return $array;
	}
	public static function get($group_id){
		if(is_numeric($group_id)){
			$result = DigiplayDB::query("SELECT web_groups.* FROM web_groups WHERE group_id = ".$group_id);
			if(pg_num_rows($result)==1)
				return pg_fetch_object($result,null,"Group");
		}
		return false;
	}
	public static function get_by_name($group_name) {
		$group_name = pg_escape_string(DigiplayDB::resource(), $group_name);
		$result = DigiplayDB::query("SELECT * FROM web_groups WHERE name = '".$group_name."'");

		if(pg_num_rows($result)==1)
			return pg_fetch_object($result,null,"Group");
		return false;
	}
}
