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
		if($this->id) DigiplayDB::update("requests", get_object_vars($this), "id = ".$this->id.";");
		else {
			$this->date = time();
			$this->id = DigiplayDB::insert("requests", get_object_vars($this), "id");
		}
		return $this->id;
	}

	public function delete() { return DigiplayDB::delete("requests", "id = ".$this->id); }
}
?>