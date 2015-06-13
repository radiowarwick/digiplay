<?php
class Playlist {
	protected $id;
	protected $name;
	protected $sortorder;
	protected $sustainer;

	public function get_id(){ return $this->id; }
	public function get_name(){ return $this->name; }
	public function get_sortorder() { return $this->sortorder; }
	public function get_sustainer() { return $this->sustainer; }

	public function set_name($name) { $this->name = $name; }
	public function set_sortorder($sortorder){ $this->sortorder = $sortorder; }
	public function set_sustainer($sustainer){ $this->sustainer = $sustainer; }

	public function save() {
		if(!$this->name) return false;
		if(isset($this->id)) DigiplayDB::update("playlists", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("playlists", get_object_vars($this), "id");
		return $this->id;
	}

	public function delete() {
		foreach($this->get_tracks() as $track) $this->del_track($track);
		return DigiplayDB::delete("playlists", "id = ".$this->id);
	}
	
	public function get_tracks($limit = 0, $offset = 0) { return Tracks::get_playlisted($this,$limit,$offset); }
	public function count_tracks() { return DigiplayDB::select("count(audioid) FROM audioplaylists WHERE playlistid = ".$this->id); }

	public function add_track($track) {	return DigiplayDB::insert("audioplaylists", array("audioid" => $track->get_id(), "playlistid" => $this->id)); }
	public function del_track($track) { return DigiplayDB::delete("audioplaylists", "audioid = ".$track->get_id()." AND playlistid = ".$this->id); }

	public function get_colour() {
		return DigiplayDB::select("colour FROM playlistcolours WHERE playlistid = ".$this->id);
	}
}
