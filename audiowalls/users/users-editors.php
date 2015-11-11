<?php
require_once('pre.php');

Output::set_title("Audiowall Users");
MainTemplate::set_subtitle("<span id=\"wall-description\"></span><span id=\"aw_edit_buttons\"><p class=\"text-success\"></p><a href=\"#\" class=\"btn btn-primary\">Add</a><a href=\"#\" class=\"btn btn-success\"></a></span>");

echo("<style type=\"text/css\">
table { font-size:1.2em; }
thead { display:none; }
.description { font-size:0.8em; font-style:italic; }
.hover-info { display:none; }
.table tbody tr.success td { background-color: #DFF0D8; }
</style>");

echo("<table class=\"table table-striped\" cellspacing=\"0\">
			<thead>
				<tr>
					<th></th>
					<th style=\"width:65px\"></th>
				</tr>
			</thead><tbody>");

$aw_set = AudiowallSets::get_by_id($_REQUEST['setid']);
$users = $aw_set->get_users_with_permissions();
if (isset($users)){
	foreach ($users as $user) {
		$userclass = Users::get_by_id($user->get_id());
		$username = $userclass->get_username();
		$permissions = $aw_set->get_user_permissions($user->get_id());					
		if($permissions[1] == "1") {
			echo("<tr><td><strong>".$username."</strong></td>");
			echo("<td class=\"delete-aw-btn\" style=\"width:65px\"><a href=\"#\" class=\"btn btn-danger\">Delete</a></td>");
			echo("</td></tr>");
		}
	}
}

echo("</tbody></table>");
?>
