<?php
class Track{
	protected $title;
	protected $artist;

	public function __construct($title = null,$artist = null){
		if(!is_null($title))
			$this->title	= $title;
		if(!is_null($artist))
			$this->artist	= $artist;
	}
	public function get_title(){
		return $this->title;
	}
	public function get_artist(){
		return $this->artist;
	}

	public function get_artist_image() {
		$artist = strtolower($this->artist);
		if (substr($artist,-5) == ', the') {
			$artist = "the ". substr($artist,0,strlen($artist)-5);
		}
		if (strpos($artist,'feat.') !== FALSE) {
			$artist = substr($artist,0,strpos($artist,' feat.'));
		}
		if (strpos($artist,'vs.') !== FALSE) {
			$artist = substr($artist,0,strpos($artist,' vs.'));
		}
		return urlencode($artist).".jpg";
	}

	public function get_tidy_artist($shorten = FALSE) {
		$return = ucwords($this->artist);
		
		if (substr($this->artist,-5) == ', The') {
			$return = "The ". substr($return,0,strlen($return)-5);
		}
		
		if ($shorten !== FALSE) {
			if (strpos($return,'Feat.') !== FALSE) {
				$return = substr($return,0,strpos($return,' Feat.'));
			}
			
			if (strlen($return) > $shorten) {
				$return = substr($return,0,$shorten)."...";
			}
		}
		
		return $return;
	}
	
	public function get_tidy_title($shorten = FALSE, $strip_brackets = FALSE) {
		$return = ucwords($this->title);
		
		if (substr($this->title,-5) == ', The') {
			$return = "The ". substr($return,0,strlen($return)-5);
		}
		
		if ($shorten !== FALSE) {
			$strip_brackets = TRUE;
		}

		if ($strip_brackets === TRUE) {
			$bracket = strpos($return, '(');
			if ($bracket !== FALSE) {
				$return = substr($return,0,$bracket);
			}
		}

		if ($shorten !== FALSE) {
			if (strlen($return) > $shorten) {
				$return = substr($return,0,$shorten)."...";
			}
		}
		
		return $return;
	}
}
