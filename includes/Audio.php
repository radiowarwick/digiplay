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
}