<?php 
if(!Session::is_group_user("Importer")) {
	Output::http_error(403);
} else {
	$current_archive = Archives::get_playin();
	$path = (is_dir($current_archive->get_localpath())? $current_archive->get_localpath() : (is_dir($current_archive->get_remotepath()) ? $current_archive->get_remotepath() : die(json_encode(array('error'=>'Playin archive inaccessible')))));
	$path = $path."/inbox/";
	$tempname = rand(0,1000).".pcm";
	$output = array();

	exec("/usr/bin/ffmpeg -i \"".FILE_ROOT."uploads/".$_REQUEST["filename"]."\" -f s16le -acodec pcm_s16le -ar 44100 -ac 2 ".$path.$tempname." 2>&1", $output);
	if(substr(end($output),0,5) != "video") die(json_encode(array("error" => "ffmpeg could not convert file", "debug" => $output)));

	$md5 = md5_file($path.$tempname);
	exec("mv ".$path.$tempname." ".$path.$md5);

	$xml = new ImportXML($md5,$_REQUEST["title"],"music",$_REQUEST["origin"],$_REQUEST["creationdate"],array_map('trim',explode(";",$_REQUEST["artist"])),$_REQUEST["album"],$_REQUEST["year"]);
	$output = file_put_contents($path.$md5.".xml",$xml->output());
	if($output === false) die(json_encode(array("error" => "could not save XML file for audio")));

	$output = array();
	exec("dpsadmin -M --import-md5 ".$md5, $output);
	echo(json_encode($output));

	$output = array();
	exec("sudo flacinate.sh ".$md5,$output);
	if($output[1] != "Success!") die(json_encode(array("error" => "could not convert audio to flac", "debug" => $output)));

	$output = unlink(FILE_ROOT."uploads/".$_REQUEST["filename"]);
	if($output === false) die(json_encode(array("error" => "could not remove uploaded file")));
}
?>
