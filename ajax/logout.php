<?php
require_once("pre.php");
Output::set_template();
Session::logout();
header("Location: ". SITE_LINK_ABS);
?>