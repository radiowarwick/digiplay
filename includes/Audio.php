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
	
	public function move_to_trash() {
		$sql = DigiplayDB::query("UPDATE audiodir SET dirid = 3 WHERE audioid = ".$this->id.";");
		if($sql) return true;
		return false;
	}

	public function fetch_from_trash() {
		$sql = DigiplayDB::query("UPDATE audiodir SET dirid = 2 WHERE audioid = ".$this->id.";");
		if($sql) return true;
		return false;
	}

	public function player() {
		Output::add_script(LINK_ABS."js/observer.js");
		Output::add_script(LINK_ABS."js/wavesurfer.js");
		Output::add_script(LINK_ABS."js/webaudio.js");
		Output::add_script(LINK_ABS."js/drawer.js");
		Output::add_script(LINK_ABS."js/drawer.svg.js");
		Output::add_script(LINK_ABS."js/wavesurfer_init.js");

		$html = "
		<script> $(function () { wavesurfer['".$this->get_id()."'] = wv_create('#waveform".$this->get_id()."', '".LINK_ABS."lib/preview/preview.php?id=".$this->get_id()."'); }); </script>
		<div class=\"row\">
			<div class=\"col-xs-12\">
				<div class=\"well well-sm\">
					<div style=\"width: 100%; margin: auto;\">
						<div style=\"width: 65px; float: left;\">
							<button class=\"btn btn-primary\" id=\"playpause\" disabled style=\"height: 60px; width: 60px; font-size: 2.2em; padding-left: 15px; border-radius: 100%;\">
								".Bootstrap::glyphicon("play")."
							</button>
							<h6><small id=\"timings\">00:00 / 00:00</small></h6>
						</div>
						<div style=\"margin-left: 70px;\">
							<div id=\"waveform".$this->get_id()."\" style=\"height: 80px; position: relative\">
								<div id=\"progress-div\" class=\"progress progress-striped\" style=\"position: absolute; width: 100%; top: 30px\">
									<div class=\"progress-bar\">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>";
		return $html;
	}
}