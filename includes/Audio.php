<?php
class Audio {
	protected $id;
	protected $md5;
	protected $archive;
	protected $length_smpl;
	protected $start_smpl;
	protected $end_smpl;
	protected $type;
	protected $creator;
	protected $creation_date;
	protected $import_date;
	protected $title;
	protected $origin;
	protected $notes;
	protected $rip_result;
	protected $filetype;

	public function get_id() {
		return $this->id;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_length() {
		return $this->length_smpl / 44100;
	}

	public function get_import_date() {
		return $this->import_date;
	}

	public function get_origin() {
		return $this->origin;
	}

	public function get_notes() {
		return $this->notes;
	}

	public function get_filetype() {
		return $this->filetype;
	}

	/* Extended functions */
	public function get_length_formatted() {
		$time_arr = Time::seconds_to_dhms($this->get_length());
		$time_str = ($time_arr["days"])? $time_arr["days"]."d " : "";
		$time_str .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
		$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "0m ";
		$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]."s " : "0s ";
		return $time_str;
	}
}