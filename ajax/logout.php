<?php
require_once("pre.php");

Session::logout();
header("Location: ". SITE_LINK_ABS);
?>