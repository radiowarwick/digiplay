<?php
require_once("pre.php");

Session::logout();
header("Location: ". LINK_ABS);
?>