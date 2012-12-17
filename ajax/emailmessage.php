<?php
require_once("pre.php");

if (is_numeric($_GET['id'])) {
    $email = Emails::get_by_id($_GET['id']);
    echo("<h4>".$email->get_subject()."<small>".str_replace("\n", "",str_replace("<", " &lt;",str_replace(">", "&gt;",$email->get_sender())))."</small></h4>");
    echo nl2br(trim(strip_tags($email->get_body())));
	//echo nl2br($email->get_body());
}
