<?php
require_once("pre.php");
Output::set_template();

if(Session::login($_POST['username'],$_POST['password'])){
	exit "success";
}else{
	exit "Incorrect username or password";
}
?>