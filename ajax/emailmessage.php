<?php


if (is_numeric($_GET['id']) && Session::is_group_user("Email Viewer")) {
	$email = Emails::get_by_id($_REQUEST['id']);
	echo(json_encode(array("subject"=>$email->get_subject(), "sender" => $email->get_sender(), "message" => $email->get_body_formatted(false))));
}
