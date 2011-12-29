<?php
class Groups{
	public static function all(){
		$result = DigiplayDB::query("SELECT * FROM web_groups ORDER BY web_groups.group");
		$array = array();
		while($object = pg_fetch_object($result,null,'Group'))
			$array[] = $object;
		return $array;
	}
	public static function get($groupid){
		if(is_numeric($groupid)){
			$result = DigiplayDB::query("SELECT web_groups.*,web_users_groups.admin AS admin FROM web_groups
			RIGHT OUTER JOIN web_users_groups ON
			(web_groups.groupid = web_users_groups.groupid AND username = '".Session::get_username()."')
			WHERE web_groups.groupid = '".$groupid."'
			ORDER BY groupid ASC");
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
