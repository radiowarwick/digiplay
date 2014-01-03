<?php
class Advert extends Audio {
	public function save() { return DigiplayDB::update("audio", get_object_vars($this), "id = ".$this->id); }

	/* Extended functions */
	public function get_artists() { return Artists::get_by_audio($this); }
	public function get_keywords() { return Keywords::get_by_audio($this); }

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
}

?>