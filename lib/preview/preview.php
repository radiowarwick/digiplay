<?php

require_once("pre.php");
Output::set_template();
$audio = Tracks::get($_GET['id']);

if(Session::is_group_user("Music Admin")) {
	$multi = 24;
	$bitrate = "192";
} else {
	$multi = 6;
	$bitrate = "48";
}

header('Content-Length: '. ($multi*1000)*$audio->get_length() .'');
header('Content-type: audio/mpeg');
header('accept-ranges: bytes');

ob_end_flush();

//echo $audio->get_mp3();
$md5 = $audio->get_md5();
$fl = substr($md5, 0, 1);

if($audio->get_filetype() == "raw") $command = "lame --silent -r -m s --bitwidth 16 -s 44.1 -b ".$bitrate." -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5." -";
if($audio->get_filetype() == "flac") $command = "flac -c -d ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac | lame --silent -m s --bitwidth 16 -s 44.1 -b ".$bitrate." -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" - -";
if($audio->get_filetype() == "mp3") $command = "lame --silent -m s --bitwidth 16 -s 44.1 -b ".$bitrate." -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".mp3 -";

$handle = popen($command, 'r');
while($read = fread($handle, 2096)) echo $read;
pclose($handle);
?>
