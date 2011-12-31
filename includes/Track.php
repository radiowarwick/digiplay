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
}
