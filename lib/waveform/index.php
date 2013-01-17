<?php
require_once("pre.php");
Output::set_template();
header("Content-type: image/png");

$audio = Tracks::get($_REQUEST['id']);

$md5 = $audio->get_md5();
$fl = substr($md5, 0, 1);
$filetype = ($audio->get_filetype() == "raw"? "" : ".".$audio->get_filetype());
$command = SITE_FILE_PATH."lib/waveform/waveform --width 1240 --height 160 --color-bg EEEEEEff --color-center FFFFFF00 --color-outer FFFFFF00 ".$audio->get_archive()->get_localpath()."/".$fl."/".$md5.$filetype." -";

$handle = popen($command, 'r');
while(
$read = fread($handle, 2096)
) {
	echo $read;
}
pclose($handle)

?>
