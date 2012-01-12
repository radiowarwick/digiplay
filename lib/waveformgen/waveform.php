<?php
require_once("pre.php");
Output::set_template();

$audio = Tracks::get($_GET['id']);

header("Content-type: image/png");

echo $audio->get_waveform_png();
?>
