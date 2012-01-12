<?php
class Audio {
	protected $id;
	protected $md5;
	protected $archive;
	protected $length_smpl;
	protected $type;
	protected $creator;
	protected $creation_date;
	protected $import_date;
	protected $title;
	protected $origin;
	protected $notes;
	protected $rip_result;
	protected $filetype;

	public function get_id() { return $this->id; }
	public function get_md5() { return $this->md5; }
	public function get_archive() { return Archives::get($this->archive); }
	public function get_length() { return $this->length_smpl / 44100; }
	public function get_type() { return AudioTypes::get_by_id($this->type); }
	public function get_creator() { return Users::get_by_id($this->creator); }
	public function get_creation_date() { return $this->creation_date; }
	public function get_import_date() { return $this->import_date; }
	public function get_title() { return $this->title; }
	public function get_origin() { return $this->origin; }
	public function get_notes() { return $this->notes; }
	public function get_rip_result() { return $this->rip_result; }
	public function get_filetype() { return $this->filetype; }

	public function set_type($audiotype) { $this->type = $audiotype->get_id(); }
	public function set_creator($user) { $this->creator = $user->get_id(); }
	public function set_title($title) { $this->title = $title; }
	public function set_origin($origin) { $this->origin = $origin; }
	public function set_notes($notes) { $this->notes = $notes; }

	public function save_audio() {
		$sql = "UPDATE audio SET (type,creator,title,origin,notes) = (".pg_escape_string($this->type).",".pg_escape_string($this->creator).",'".pg_escape_string($this->title)."','".pg_escape_string($this->origin)."','".pg_escape_string($this->notes)."') WHERE id = ".$this->id.";";
		return (bool) DigiplayDB::query($sql);
	}

	/* Extended functions */
	public function get_length_formatted() {
		$time_arr = Time::seconds_to_dhms($this->get_length());
		$time_str = ($time_arr["days"])? $time_arr["days"]."d " : "";
		$time_str .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
		$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "0m ";
		$time_str .= ($time_arr["seconds"])? sprintf('%02d',$time_arr["seconds"])."s " : "00s ";
		return $time_str;
	}

	public function get_waveform_png(){
		$md5 = $this->get_md5();
		$fl = substr($md5, 0, 1);
		$command = SITE_FILE_PATH."lib/waveformgen/waveformgen -m -d 1240x160 -b EEEEEE -r FFFFFF -p FFFFFF ".$this->get_archive()->get_localpath()."/".$fl."/".$md5." tmp.png";
		exec($command, $output);
		return readfile(SITE_FILE_PATH."lib/waveformgen/tmp.png");
	}
}