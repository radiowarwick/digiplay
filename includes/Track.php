<?php
class Track extends Audio{
	protected $music_album;
	protected $music_track;
	protected $music_released;
	protected $reclibid;
	protected $sustainer;
	protected $flagged;
	protected $censor;

	public function get_album() { return Albums::get_by_audio_id($this->id); }
	public function get_track() { return $this->music_track; }
	public function get_year() { return $this->music_released; }
	public function get_reclibid() { return $this->reclibid; }
	public function is_sustainer(){ return (($sustainer == "t")? TRUE : FALSE); }
	public function is_flagged() { return (($flagged == "t")? TRUE : FALSE); }
	public function is_censored(){ return (($censored == "t")? TRUE : FALSE); }

	public function set_album($album) { $this->music_album = $album->get_id(); }
	public function set_track($track) { $this->track = $track; }
	public function set_year($year) { $this->music_released = $year; }
	public function set_reclibid($reclibid) { $this->reclibid = $reclibid; }
	public function set_sustainer($sustainer) { $this->sustainer = $sustainer? "t":"f"; }
	public function set_flagged($flagged) { $this->flagged = $flagged? "t":"f"; }
	public function set_censor($censor) { $this->censor = $censor? "t":"f";}

	/* Extended functions */
	public function get_artists() { return Artists::get_by_audio_id($this->id); }
	public function get_keywords() { return Keywords::get_by_audio_id($this->id); }
	
	public function get_artists_str() {
		$artists = $this->get_artist();
		foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
		$artist_str = substr($artist_str,0,-2);
		return $artist_str;
	}

	public function add_artists($artists) {
		if(!is_array($artists)) {
			$tmp = $artists;
			$artists = array($tmp);
		}
		foreach($artists as $artist) {
			$artist->add_to_track($this->id);
		}
	}

	public function del_artists($artists) {
		if(!is_array($artists)) {
			$tmp = $artists;
			$artists = array($tmp);
		}
		foreach($artists as $artist) {
			$artist->del_from_track($this->id);
		}
	}
		
	public function add_keywords($keywords) {
		if(!is_array($keywords)) {
			$tmp = $keywords;
			$keywords = array($tmp);
		}
		foreach($keywords as $keyword) {
			$keyword->add_to_track($this->id);
		}
	}

	public function del_keywords($keywords) {
		if(!is_array($keywords)) {
			$tmp = $keywords;
			$keywords = array($tmp);
		}
		foreach($keywords as $keyword) {
			$keyword->del_from_track($this->id);
		}
	}
}

?>