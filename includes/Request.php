<?php
class Request {
	protected $id;
	protected $name;
	protected $artistname;
	protected $date;
	protected $userid;

	public function get_id() { return $this->id; }
	public function get_name() { return $this->name; }
	public function get_artist_name() { return $this->artistname; }
	public function get_date() { return $this->date; }
	public function get_user() { return Users::get_by_id($this->userid); }

	public function set_name($name) { $this->name = $name; }
	public function set_artist_name($artist_name) { $this->artistname = $artist_name; }
	public function set_user($user) { $this->userid = $user->get_id(); }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::query("UPDATE requests SET name = '".pg_escape_string($this->name)."', artistname = '".pg_escape_string($this->artistname)."', date = ".time().", userid = ".$this->userid." WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO requests (name,artistname,date,userid) VALUES ('".pg_escape_string($this->name)."','".pg_escape_string($this->artistname)."',".time().",".$this->userid.") RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}

	public function delete() {
		return pg_fetch_result(DigiplayDB::query("DELETE FROM requests WHERE id = ".$this->id));
	}
}
?>