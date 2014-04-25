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

	public function get_id() { return $this->id; }
	public function get_md5() { return $this->md5; }
	public function get_archive() { return Archives::get($this->archive); }
	public function get_length() { return ($this->end_smpl - $this->start_smpl) / 44100; }
	public function get_start() { return $this->start_smpl / 44100; }
	public function get_end() { return $this->end_smpl / 44100; }
	public function get_type() { return AudioTypes::get_by_id($this->type); }
	public function get_creator() { return Users::get_by_id($this->creator); }
	public function get_creation_date() { return $this->creation_date; }
	public function get_import_date() { return $this->import_date; }
	public function get_title() { return $this->title; }
	public function get_origin() { return $this->origin; }
	public function get_notes() { return $this->notes; }
	public function get_rip_result() { return $this->rip_result; }
	public function get_filetype() { return $this->filetype; }

	public function set_length_smpl($length_smpl) { $this->length_smpl = $length_smpl; }
	public function set_start_smpl($start_smpl) { $this->start_smpl = $start_smpl; }
	public function set_end_smpl($end_smpl) { $this->end_smpl = $end_smpl; }
	public function set_type($audiotype) { $this->type = $audiotype->get_id(); }
	public function set_creator($user) { $this->creator = $user->get_id(); }
	public function set_title($title) { $this->title = $title; }
	public function set_origin($origin) { $this->origin = $origin; }
	public function set_notes($notes) { $this->notes = $notes; }

	public function save() {
		if(isset($this->id)) DigiplayDB::update("audio", get_object_vars($this), "id = ".$this->id);
		else {
			if(!isset($this->md5) || !isset($this->archive) || !isset($this->filetype)) return false;
			if(!isset($this->end_smpl)) $this->end_smpl = $this->length_smpl;
			if(!isset($this->start_smpl)) $this->start_smpl = 0;
			if(!isset($this->import_date)) $this->import_date = time();
			if(!isset($this->type)) $this->set_type(AudioTypes::get_by_name(get_class()));

			$this->id = DigiplayDB::insert("audio", get_object_vars($this), "id");
		}
		return $this->id;
	}

	/* Extended functions */
	public function get_length_formatted() {
		$time_arr = Time::seconds_to_dhms($this->get_length());
		$time_str = (isset($time_arr["days"]))? $time_arr["days"]."d " : "";
		$time_str .= (isset($time_arr["hours"]))? $time_arr["hours"]."h " : "";
		$time_str .= (isset($time_arr["minutes"]))? $time_arr["minutes"]."m " : "0m ";
		$time_str .= (isset($time_arr["seconds"]))? sprintf('%02d',$time_arr["seconds"])."s " : "00s ";
		return $time_str;
	}
	
	public function move_to_trash() { return DigiplayDB::update("audiodir", array("dirid" => 3), "audioid = ".$this->id); }
	public function fetch_from_trash() { return DigiplayDB::update("audiodir", array("dirid" => 2), "audioid = ".$this->id); }

	public function player() {
		Output::add_script(LINK_ABS."js/observer.js");
		Output::add_script(LINK_ABS."js/wavesurfer.js");
		Output::add_script(LINK_ABS."js/webaudio.js");
		Output::add_script(LINK_ABS."js/drawer.js");
		Output::add_script(LINK_ABS."js/drawer.svg.js");
		Output::add_script(LINK_ABS."js/wavesurfer_init.js");

		$html = "
		<script> $(function () { wv_create('".$this->id."'); wavesurfer[".$this->id."].load('".LINK_ABS."audio/preview/".$this->id.".mp3') }); </script>
		<div class=\"row audio-player\" id=\"".$this->id."\">
			<div class=\"col-xs-12\">
				<div class=\"well well-sm\">
					<div>
						<div>
							<button class=\"btn btn-primary playpause\" id=\"playpause\" disabled>
								".Bootstrap::glyphicon("play")."
							</button>
							<h6><small><span class=\"elapsed\">00:00</span> / <span class=\"duration\">00:00</span></small></h6>
						</div>
						<div>
							<div id=\"waveform".$this->get_id()."\">
								<div id=\"progress-div\" class=\"progress progress-striped\">
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

	public function get_filename() {
		$archive = $this->get_archive();
		$path = (is_dir($archive->get_localpath())? $archive->get_localpath() : (is_dir($archive->get_remotepath()) ? $archive->get_remotepath() : ""));
		return $path."/".substr($this->md5, 0, 1)."/".$this->md5.".flac";
	}

	public function update_metadata() {
		$output = shell_exec("metaflac --remove-all-tags ".$this->get_filename()." --set-tag=\"TITLE=".$this->title."\" --set-tag=\"ARTIST=".$this->get_artists_str()."\" ".(isset($this->album)? "--set-tag=\"ALBUM=".$this->get_album()->get_name()."\" " : "")."--set-tag=\"ENCODED-BY=Digiplay\"");
		if($output === false) return false;
		else return true;
	}

	public function calculate_replaygain() {
		$output = shell_exec("metaflac --add_replay_gain ".$this->get_filename());
		if($output === false) return false;
		else return true;
	}

	public static function get_by_id($id) {
		$type = DigiplayDB::select("type FROM audio WHERE id = ".$id);
		if($type) {
			if($type == 1) return Tracks::get_by_id($id);
			else if($type == 2) return Jingles::get_by_id($id);
			else if($type == 3) return Adverts::get_by_id($id);
			else if($type == 4) return Prerecs::get_by_id($id);
		}
	}

	public static function get_by_md5($md5) {
		$type = DigiplayDB::select("type FROM audio WHERE md5 = '".$md5."'");
		if($type) {
			if($type == 1) return Tracks::get_by_md5($md5);
			else if($type == 2) return Jingles::get_by_md5($md5);
			else if($type == 3) return Adverts::get_by_md5($md5);
			else if($type == 4) return Prerecs::get_by_md5($md5);
		}
	}
}