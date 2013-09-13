<?php
Output::set_template();

$audio = Tracks::get($_GET["id"]);
$filetypes = array(
	"mp3" => "audio/mpeg"
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

if(Session::is_group_user("Music Admin")) {
	$multi = 24;
	$bitrate = "192";
} else {
	$multi = 6;
	$bitrate = "48";
}

header("Content-type: ".$filetype);
header('Content-Length: '. ($multi*1000)*$audio->get_length());
header("accept-ranges: bytes");

if(Session::is_group_user("Music Admin")) {
	$multi = 24;
	$bitrate = "192";
} else {
	$multi = 6;
	$bitrate = "48";
}

ob_end_flush();
if(session_id()) session_write_close();

$md5 = $audio->get_md5();
$fl = substr($md5, 0, 1);

if($filetype == "mp3") $command = "flac -c -d ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.".flac | lame --silent -m s --bitwidth 16 -s 44.1 -b ".$bitrate." -q 9 -c --id3v1-only --tt \"Track Preview\" --ta \"Digiplay\" --tl \"Digiplay Music Database\" - -";

$handle = popen($command, 'r');
while($read = fread($handle, 8192)) echo $read;
pclose($handle);

?>