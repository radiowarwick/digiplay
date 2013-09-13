<?php
Output::set_template();

$audio = Tracks::get($_GET["id"]);
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
	parse_str(substr($_SERVER["HTTP_REFERER"],strpos($_SERVER["HTTP_REFERER"],"?") +1),$parameters);
	if(is_null(Locations::get_by_key($_REQUEST["key"]))) exit(http_response_code(401));
}

header("Content-type: ".$filetype);
header("accept-ranges: bytes");
if($filetype == "flac") header("Content-Length: ".filesize($audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac"));
if($filetype == "mp3") header("Content-Length: ". 32000*$audio->get_length());
if($filetype == "wav") header("Content-Length: ". 176426*$audio->get_length());

ob_end_flush();
if(session_id()) session_write_close();

$md5 = $audio->get_md5();
$fl = substr($md5, 0, 1);

if($filetype == "flac") $command = "cat ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac";
if($filetype == "mp3") $command = "flac -c -d ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac | lame --silent -m s --bitwidth 16 -s 44.1 -b 256 -q 9 -c --id3v1-only --tt \"".$audio->get_title()."\" --ta \"".$audio->get_artists_str()."\" --tl \"".$audio->get_album()->get_name()."\" - -";
if($filetype == "wav") $command = "flac -c -d ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac";

$handle = popen($command, 'r');
while($read = fread($handle, 8192)) echo $read;
pclose($handle);

?>
