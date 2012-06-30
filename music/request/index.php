<?php
require_once('pre.php');
Output::set_title("Requested Tracks");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
Output::add_script(SITE_LINK_REL."js/bootstrap-popover.js");

MainTemplate::set_subtitle("Want to play a track, but it's not in the database? Request it here");

if($_REQUEST["name"] && $_REQUEST["artistname"]) {
	$request = new Request();
	$request->set_name($_REQUEST["name"]);
	$request->set_artist_name($_REQUEST["artistname"]);
	$request->set_user(Session::get_user());
	$request->save();
}

if($_REQUEST["delete"]) {
	if(!Session::is_group_user("Music Admin")) {
		echo AlertMessage::basic("error","You are trying to delete a request, but you do not have the requred privelidges!","Error!");
	} else {
		$request = Requests::get_by_id($_REQUEST["delete"]);
		if($request) $request->delete();
	}
}

echo("
<form action=\"\" method=\"post\" name=\"request-track\">
	<fieldset>
		<div class=\"clearfix\">
			<div class=\"pull-right\">
				<input type=\"text\" name=\"artistname\" placeholder=\"Artist...\" class=\"span3\">
				<input type=\"text\" name=\"name\" placeholder=\"Title...\" class=\"span3\">
				<input type=\"submit\" value=\"Request\" class=\"btn primary\">
			</div>
			<h3 style=\"margin-top: -4px\">Request a track</h3>
		</div>
	</fieldset>
</form>");

if($requested = Requests::get_all()) {
	echo("
<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"date\">Date Requested</th>
			<th class=\"requester\">Requester</th>
			".(Session::is_group_user("Music Admin")? "
			<th class=\"icon\"></th>
			<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($requested as $request) {
		echo("
	<tr id=\"".$request->get_id()."\">
		<td class=\"artist\">".$request->get_artist_name()."</td>
		<td class=\"title\">".$request->get_name()."</td>
		<td class=\"date\">".date("d/m/Y H:i",$request->get_date())."</td>
		<td class=\"requester\">".$request->get_user()->get_username()."</td>".(Session::is_group_user("Music Admin")? "
		<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/upload/file?title=".$request->get_name()."&artist=".$request->get_artist_name()."\" class=\"request-upload\" title=\"Upload this track\" rel=\"twipsy\"><i class=\"icon-upload\"></i></td>
		".(Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/request/?delete=".$request->get_id()."\" class=\"request-delete\" title=\"Delete this request\" rel=\"twipsy\"><i class=\"icon-remove-sign\"></i></td>" : "") : "")."
	</tr>");
	}
	echo("
</table>");
} else {
	echo("
<strong>No new requested tracks.</strong>");
}
?>