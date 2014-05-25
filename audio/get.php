<?php
Output::set_template();

if(is_numeric($_GET["id"])) $audio = Audio::get_by_id($_GET["id"]);
else $audio = Audio::get_by_md5($_GET["id"]);

$filetypes = array(
	"mp3" => "audio/mpeg",
	"flac" => "audio/flac",
	"wav" => "audio/wav"
	);
if(array_key_exists($_GET["filetype"],$filetypes)) {
	$filetype = $_GET["filetype"];
	$mimetype = $filetypes[$_GET["filetype"]];
}
else exit(http_response_code(400));

if(!Session::is_group_user("Music Admin")) {
	if(!isset($_GET["key"]) || is_null(Locations::get_by_key($_GET["key"]))) exit(http_response_code(401));
}


$md5 = $audio->get_md5();
$fl = substr($md5, 0, 1);

header("Content-type: ".$filetype);
header("accept-ranges: bytes");
if($filetype == "flac") header("Content-Length: ".filesize($audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac"));
if($filetype == "mp3") header("Content-Length: ". 32000*$audio->get_length());
if($filetype == "wav") header("Content-Length: ". 176426*$audio->get_length());

ob_end_flush();
if(session_id()) session_write_close();

if($filetype == "flac") $command = "cat ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac";
if($filetype == "mp3") $command = "sox ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac -t mp3 -C 256.2 - trim ".$audio->get_start()." ".$audio->get_end();
if($filetype == "wav") $command = "sox ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac -t wav - trim ".$audio->get_start()." ".$audio->get_end();

$handle = popen($command, 'r');
while($read = fread($handle, 8192)) echo $read;
pclose($handle);

?>
