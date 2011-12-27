<?php
class DigiplayTrack extends Track {

	protected $dps_playlistid;
	protected $dps_id;

	public function get_playlistid(){
		return $this->dps_playlistid;
	}
	public function get_id(){
		return $this->dps_id;
	}
}
?>

