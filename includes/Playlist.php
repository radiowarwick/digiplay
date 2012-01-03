<?php
class Playlist {
	protected $name;
	protected $id;
	protected $tracks;

	public function get_name(){
		return $this->name;
	}
	public function get_id(){
		return $this->id;
	}
	
	public function get_tracks(){
		return $this->tracks;
	}
	
	public function num_tracks(){
		return count($this->tracks);
	}

	public function add_track($track){
		$this->tracks[] = $track;
	}
}
