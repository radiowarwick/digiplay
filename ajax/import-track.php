
<?php 
if(!Session::is_group_user("Importer")) {
	Output::http_error(403);
} else {
	if(!isset($_REQUEST["filename"])) die(json_encode(array("error" => "invalid input file")));
	$uploaded_file = utf8_decode(FILE_ROOT."uploads/".$_REQUEST["filename"]);

	if(!isset($_REQUEST["type"])) $_REQUEST["type"] = "music";

	if(!isset($_REQUEST["title"]) || $_REQUEST["title"] === "") die(json_encode(array("error" => "You must specify a title")));

	$current_archive = Archives::get_playin();
	$path = (is_dir($current_archive->get_localpath())? $current_archive->get_localpath() : (is_dir($current_archive->get_remotepath()) ? $current_archive->get_remotepath() : die(json_encode(array('error'=>'Playin archive inaccessible')))));

	if(!is_writable($path)) die(json_encode(array("error" => "Audio archive is not writable")));

	$tempfile = tempnam(sys_get_temp_dir(), 'dps').".".pathinfo($uploaded_file, PATHINFO_EXTENSION);
	copy($uploaded_file, $tempfile);

	$md5 = md5_file($tempfile);
	$output = array();

	# Execute SoX to convert our audio
	# Trim silence from beginning and end (1% volume threshold)
	# Convert to 44.1kHz 16-bit stereo for consistency
	# Normalise to -0.1dB
	# Save as flac in inbox
	exec("sox \"".$tempfile."\" -b 16 \"".$path."/inbox/".$md5.".flac\" silence 1 0.1 1% reverse silence 1 0.1 1% reverse channels 2 rate 44100 gain -n -0.1 2>&1", $output);
	if(strpos(implode($output), "FAIL")) {
		unlink($tempfile);
		die(json_encode(array("error" => "SoX could not convert file", "debug" => $output)));
	}

	switch($_REQUEST["type"]) {
		case "music":
			$audio = new Track();
			if(isset($_REQUEST["album"]) && $_REQUEST["album"] != "") $audio->set_album($_REQUEST["album"]);
			else $audio->set_album("(none)");

			break;

		case "jingle":
			$audio = new Jingle();

			break;

		case "advert":
			$audio = new Advert();

			break;

		case "prerec":
			$audio = new Prerec();
			break;
	}

	if(isset($_REQUEST["origin"])) $audio->set_origin($_REQUEST["origin"]);
	if(isset($_REQUEST["year"])) $audio->set_year($_REQUEST["year"]);

	$audio->set_title($_REQUEST["title"]);

	$audio->set_length_smpl(shell_exec("soxi -s \"".$path."/inbox/".$md5.".flac\""));
	$audio->set_md5($md5);
	$audio->set_archive($current_archive);
	$audio->set_filetype("flac");

	if(!$audio->save()) {
		unlink($tempfile);
		die(json_encode(array("error" => "Failed to save audio entry to database.", "debug" => Errors::report("array"))));
	}

	if(isset($_REQUEST["artist"])) $audio->add_artists(explode(";",$_REQUEST["artist"]));

	$output = rename($path."/inbox/".$md5.".flac", $path."/".substr($md5, 0, 1)."/".$md5.".flac");
	if($output === false) {
		unlink($tempfile);
		die(json_encode(array("error" => "could not import file to audio archive", "debug" => Errors::report("array"))));
	}

	unlink($tempfile);
	$output = unlink($uploaded_file);
	if($output === false) die(json_encode(array("error" => "could not remove uploaded file")));

	$audio->update_metadata();
	$audio->calculate_replaygain();

	echo(json_encode(array("response"=>"success", "id" => $audio->get_id())));
}
?>
