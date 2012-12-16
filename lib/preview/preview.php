<?php

require_once("pre.php");
Output::set_template();
$audio = Tracks::get($_GET['id']);
header('Content-Length: '. (6000)*$audio->get_length() .'');
header('Content-type: audio/mpeg');
header('accept-ranges: bytes');

ob_end_flush();

//echo $audio->get_mp3();
$md5 = $audio->get_md5();
$fl = substr($md5, 0, 1);

if($audio->get_filetype() == "raw") $command = "/usr/local/bin/lame --silent -r -m s --bitwidth 16 -s 44.1 -b 48 -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5." -";
if($audio->get_filetype() == "flac") $command = "/usr/bin/flac -c -d ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac | /usr/local/bin/lame --silent -m s --bitwidth 16 -s 44.1 -b 48 -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" - -";
if($audio->get_filetype() == "mp3") $command = "/usr/local/bin/lame --silent -m s --bitwidth 16 -s 44.1 -b 48 -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".mp3 -";

$handle = popen($command, 'r');
while(
$read = fread($handle, 2096)
) {
	echo $read;
}
pclose($handle);
?>