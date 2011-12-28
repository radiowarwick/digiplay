<?php
require_once("pre.php");
Output::set_template();

if(Session::login($_POST['username'],$_POST['password'])){
	echo "success";
}else{
	echo "error";
}
?>