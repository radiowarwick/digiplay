<?php
class Track extends Audio{
	protected $music_album;
	protected $music_track;
	protected $music_released;
	protected $reclibid;
	protected $sustainer;
	protected $flagged;
	protected $censor;


	public function get_artist(){
		return Artists::get_by_audio_id($this->id);
	}

	public function get_album(){
		return Albums::get_by_audio_id($this->id);
	}

	public function get_keywords() {
		return Keywords::get_by_audio_id($this->id);
	}

	public function get_year(){
		return $this->music_released;
	}

	public function get_reclibid() {
		return $this->reclibid;
	}

	public function is_sustainer(){
		return (($sustainer == 't')? TRUE : FALSE);
	}

	public function is_censored(){
		return (($censored == 't')? TRUE : FALSE);
	}

	public function is_flagged() {
		return (($flagged == 't')? TRUE : FALSE);
	}

	/* Extended functions */
	public function get_artists_str() {
		$artists = $this->get_artist();
		foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
		$artist_str = substr($artist_str,0,-2);
		return $artist_str;
	}
}

?>