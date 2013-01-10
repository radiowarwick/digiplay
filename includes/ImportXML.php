<?php
class ImportXML {
	private $md5;
	private $title;
	private $creationdate;
	private $importdate;
	private $creator;
	private $type;
	private $artists;
	private $album;
	private $year;

	public function __construct($md5,$title,$type,$creator,$creationdate=null,$artists=null,$album=null,$year=null) {
		$this->md5 = $md5;
		$this->title = $title;
		$this->type = $type;
		$this->creator = $creator;
		$this->creationdate = $creationdate? $creationdate : date("Y-m-d");
		$this->importdate = date();
		$this->artists = $artists;
		$this->album = $album;
		$this->year = $year;
	}

	public function output() {
		$output = "<?xml version\"1.0\">
<!DOCTYPE audio_v1 SYSTEM \"dps.dtd\">
<audio md5=\"".$this->md5."\"
	filetype=\"raw\"
	creationdate=\"".$this->creationdate."\"
	creator=\"".Session::get_username()."\"
	importdate=\"".$this->importdate."\"
	ripresult=\"Website Upload\"
	type=\"".$this->type."\">
	<segment>
		<title>".$this->title."</title>
";
		foreach($this->artists as $artist) $output .= "
		<artist name=\"".$artist."\" />";

		$output .= "
		<album name=\"".$this->album."\"
			origin=\"".$this->creator."\"
			released=\"".$this->year."\" />
		<tracknum>0</tracknum>
		<smpl length=\"0\"
			trim_start=\"0\"
			trim_end=\"0\"
			fade_in=\"0\"
			fade_out=\"0\" />
	</segment>
</audio>
		";
		return str_replace("\r", "", $output);
	}

}
?>