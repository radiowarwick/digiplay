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
		return Artists::get($this->id);
	}

	public function get_album(){
		return Albums::get($this->music_album);
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
