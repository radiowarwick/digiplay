<?php

require_once("pre.php");
Output::set_template();
$audio = Tracks::get($_GET['id']);

if(Session::is_group_user("Music Admin")) {
	header('Content-type: audio/flac');
	header('Content-disposition: attachment; filename="'.$audio->get_artists_str().' - '.$audio->get_title().'.flac"');
	header('accept-ranges: bytes');

	ob_end_flush();

	$md5 = $audio->get_md5();
	$fl = substr($md5, 0, 1);

	if($audio->get_filetype() == "raw") $command = "flac -c --replay-gain --best --endian=little --channels=2 --bps=16 --sample-rate=44100 --sign=signed --tag TITLE=".$audio->get_title()." --tag ARTIST=".$audio->get_artists_str()." --tag ALBUM=".$audio->get_album()->get_name()." --tag DATE=".$audio->get_music_released()." ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5;
	if($audio->get_filetype() == "flac") $command = "cat ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac";
	if($audio->get_filetype() == "mp3") $command = "lame --silent -m s --bitwidth 16 -s 44.1 -b ".$bitrate." -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".mp3 -";

	if($audio->get_filetype() == "flac") header('Content-Length: '.filesize($audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac"));

	$handle = popen($command, 'r');
	while($read = fread($handle, 8192)) echo $read;
	pclose($handle);
} else {
	Output::http_error(401);
}
?>
