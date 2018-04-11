<?php

if(!isset($_GET["error"]))
	$error = 400;
else
	$error = $_GET["error"];

switch($error){
	case 401:
		header("HTTP/1.0 401 Unauthorized");
		Output::set_title("Error 401 - Permission Error");
		echo "<h2>Unauthorized</h2><p>This page you tried to reach requires special permissions</p>";
		break;
	case 404:
		header("HTTP/1.0 404 Not Found");
		Output::set_title("Error 404 - Page Not Found");
		echo "<h2>Page not found</h2><p>This page does not exist</p>";
		break;
	case 405:
		header("HTTP/1.0 405 Method Not Allowed");
		Output::set_title("Error 405 - Method Not Allowed");
		echo "<h2>Method Not Allowed</h2><p>The method you used is not permitted</p>";
		break;
	case 410:      
		header("HTTP/1.0 410 Gone");	
		Output::set_title("Error 410 - Gone");
		echo "<h2>This page has left us</h2><p>I wonder where it went?</p>";   
		break;
	case 418:      
		header("HTTP/1.0 418 I'm a Teapot");	
		Output::set_title("Error 418 - I'm a Teapot");
		echo "<h2>Am I really a teapot?</h2><p>Or are you the teapot?</p>";
		break;
	default:
		header("HTTP/1.0 400 Bad Request");
		Output::set_title("Error 400 - Bad Request");
		echo "<h2>Bad Request Received</h2><p>You sent a bad request!</p>";
		break;
}

?>