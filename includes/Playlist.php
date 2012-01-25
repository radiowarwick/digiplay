<?php
class Playlist {
	protected $id;
	protected $name;
	protected $sortorder;

	public function get_id(){
		return $this->id;
	}

	public function get_name(){
		return $this->name;
	}

	public function set_sortorder(){
		return $this->sortorder;
	}
	
	public function get_tracks(){
		return Tracks::get_playlisted($this);
	}

	public function add_track($track){
		$sql = "";
	}
}
