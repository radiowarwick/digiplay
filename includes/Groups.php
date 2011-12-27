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
	public static function getByName($groupName) {
		$groupName = pg_escape_string(DigiplayDB::resource(), $groupName);
		if(Session::is_group_user("group_admin")) {
			$result = DigiplayDB::query("SELECT web_groups.*,true AS admin FROM web_groups WHERE web_groups.group = '".$groupName."'");
		} else {
			$result = DigiplayDB::query("SELECT web_groups.*,web_users_groups.admin AS admin FROM web_groups
			RIGHT OUTER JOIN web_users_groups ON
			(web_groups.groupid = web_users_groups.groupid AND username = '".Session::get_username()."')
			WHERE web_groups.group = '".$groupName."'");
		}
		if(pg_num_rows($result)==1)
			return pg_fetch_object($result,null,"Group");
		return false;
	}
}
