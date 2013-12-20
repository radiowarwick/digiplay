<?php
class Track extends Audio {
	protected $music_album;
	protected $music_track;
	protected $music_released;
	protected $reclibid;
	protected $sustainer;
	protected $flagged;
	protected $censor;

	public function get_album() { return Albums::get_by_audio_id($this->id); }
	public function get_track() { return $this->music_track; }
	public function get_year() { return ($this->music_released == 0 )? "(not set)" : $this->music_released; }
	public function get_reclibid() { return $this->reclibid; }
	public function is_flagged() { return (($this->flagged == "t")? TRUE : FALSE); }
	public function is_censored(){ return (($this->censor == "t")? TRUE : FALSE); }

	public function set_track($track) { $this->track = $track; }
	public function set_year($year) { $this->music_released = (is_numeric($year))? $year : 0; }
	public function set_reclibid($reclibid) { $this->reclibid = $reclibid; }
	public function set_flagged($flagged) { $this->flagged = $flagged? "t":"f"; }
	public function set_censored($censor) { $this->censor = $censor? "t":"f";}

	public function save() { return DigiplayDB::update("audio", array("music_album" => $this->music_album, "music_track" => $this->music_track, "music_released" => $this->music_released, "reclibid" => $this->reclibid, "flagged" => $this->flagged, "censor" => $this->censor), "id = ".$this->id); }

	/* Extended functions */
	public function get_artists() { return Artists::get_by_audio($this); }
	public function get_keywords() { return Keywords::get_by_audio($this); }

	public function set_album($album_str) { 
		if(!Albums::get_by_name($album_str)) {
			$album = new Album;
			$album->set_name($album_str);
			$album->save();
		} else {
			$album = Albums::get_by_name($album_str);
		}
		$this->music_album = $album->get_id();
	}
	
	public function get_artists_str() {
		$artists = $this->get_artists();
		$artist_str = "";
		foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
		$artist_str = substr($artist_str,0,-2);
		return $artist_str;
	}

	public function add_artists($artists) {
		if(!is_array($artists)) $artists = array($artists);
		foreach($artists as $artist) {
			if($artist == "") continue;
			$exists = Artists::get_by_name($artist);
			if($exists) $exists->add_to_track($this);
			else {
				$object = new Artist;
				$object->set_name($artist);
				$object->add_to_track($this);
			}
		}
	}

	public function del_artists($artists) {
		if(!is_array($artists)) {
			$tmp = $artists;
			$artists = array($tmp);
		}
		foreach($artists as $artist) {
			$object = Artists::get_by_name($artist);
			$object->del_from_track($this);
		}
	}
		
	public function get_keywords_str() {
		$artists = $this->get_keywords();
		foreach($keywords as $keyword) $keyword_str .= $keyword->get_text().", ";
		$keyword_str = substr($keyword_str,0,-2);
		return $keyword_str;
	}

	public function add_keywords($keywords) {
		if(!is_array($keywords)) $keywords = array($keywords);
		foreach($keywords as $keyword) {
			if($keyword == "") return false;
			$exists = Keywords::get_by_text($keyword);
			if($exists) $exists->add_to_track($this);
			else {
				$object = new Keyword;
				$object->set_text($keyword);
				$object->add_to_track($this);
			}
		}
	}

	public function del_keywords($keywords) {
		if(!is_array($keywords)) {
			$tmp = $keywords;
			$keywords = array($tmp);
		}
		foreach($keywords as $keyword) {
			$object = Keywords::get_by_text($keyword);
			$object->del_from_track($this);
		}
	}

	public function get_playlists_in() {
		return Playlists::get_by_track($this);
	}

	public function add_to_playlist($playlist) {
		return $playlist->add_track($this);
	}

	public function del_from_playlist($playlist) {
		return $playlist->del_track($this);
	}
}

?>