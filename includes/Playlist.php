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

	public function delete() {
		foreach($this->get_tracks() as $track) $this->del_track($track);
		$query = DigiplayDB::query("DELETE FROM playlists WHERE id = ".$this->id.";");
		return ($query? true : false);
	}
	
	public function get_tracks($limit = 0, $offset = 0){
		return Tracks::get_playlisted($this,$limit,$offset);
	}

	public function count_tracks() {
		return pg_fetch_result(DigiplayDB::query("SELECT COUNT(audioid) FROM audioplaylists WHERE playlistid = ".$this->id.";"),NULL,0);
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
