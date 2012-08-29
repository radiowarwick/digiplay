<?php
class Playlist {
	protected $id;
	protected $name;
	protected $sortorder;

	public function get_id(){ return $this->id; }
	public function get_name(){ return $this->name; }
	public function get_sortorder() { return $this->sortorder; }

	public function set_name($name) { $this->name = $name; }
	public function set_sortorder($sortorder){ $this->sortorder = $sortorder; }

	public function save() {
		if(!$this->name) return false;
		if($this->id) DigiplayDB::query("UPDATE playlists SET name = '".pg_escape_string($this->name)."', sortorder = ".$this->sortorder." WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO playlists (name) VALUES ('".pg_escape_string($this->name)."') RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}
	
	public function get_tracks(){
		return Tracks::get_playlisted($this);
	}

	public function add_track($track) {
		$query = DigiplayDB::query("INSERT INTO audioplaylists (audioid,playlistid) VALUES (".$track->get_id().",".$this->id.");");
		return ($query? true : false);
	}

	public function del_track($track) {
		$query = DigiplayDB::query("DELETE FROM audioplaylists WHERE audioid = ".$track->get_id()." AND playlistid = ".$this->id.";");
		return ($query? true : false);
	}
}
