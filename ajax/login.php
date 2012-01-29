<?php
require_once("pre.php");

if(Session::login($_POST['username'],$_POST['password'])){
	exit("success");
} else {
	exit("Incorrect username or password");
}
?>