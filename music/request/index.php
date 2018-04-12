<?php

Output::http_error(410);

Output::set_title("Requested Tracks");
Output::add_stylesheet(LINK_ABS."css/music.css");
Output::add_script(LINK_ABS."js/bootstrap-popover.js");

MainTemplate::set_subtitle("Want to play a track, but it's not in the database? Request it here");

if(isset($_REQUEST["name"]) && isset($_REQUEST["artistname"])) {
	$request = new Request();
	$request->set_name($_REQUEST["name"]);
	$request->set_artist_name($_REQUEST["artistname"]);
	$request->set_user(Session::get_user());
	$request->save();
}

if(isset($_REQUEST["delete"])) {
	if(!Session::is_group_user("Music Admin")) {
		echo Bootstrap::alert_message_basic("error","You are trying to delete a request, but you do not have the requred privelidges!","Error!");
	} else {
		$request = Requests::get_by_id($_REQUEST["delete"]);
		if($request) $request->delete();
	}
}

echo("
<h3 style=\"margin-top: -4px\">Request a track</h3>
<form action=\"\" method=\"post\" name=\"request-track\" class=\"form-inline\">
	<div class=\"form-group\">
		<input type=\"text\" name=\"artistname\" placeholder=\"Artist...\" class=\"form-control\">
	</div>
	<div class=\"form-group\">
		<input type=\"text\" name=\"name\" placeholder=\"Title...\" class=\"form-control\">
	</div>
	<div class=\"form-group\">
		<input type=\"submit\" value=\"Request\" class=\"btn btn-primary\">
	</div>
</form>");

if($requested = Requests::get_all()) {
	echo("
<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			<th class=\"date nowrap\">Date Requested</th>
			<th class=\"requester nowrap\">Requester</th>
			".(Session::is_group_user("Music Admin")? "
			<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($requested as $request) {
		echo("
	<tr id=\"".$request->get_id()."\">
		<td class=\"artist\">".$request->get_artist_name()."</td>
		<td class=\"title\">".$request->get_name()."</td>
		<td class=\"date nowrap\">".date("d/m/Y H:i",$request->get_date())."</td>
		<td class=\"requester nowrap\">".$request->get_user()->get_username()."</td>
		".(Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"".LINK_ABS."music/request/?delete=".$request->get_id()."\" class=\"request-delete\" title=\"Delete this request\" rel=\"twipsy\">".Bootstrap::fontawesome("times-circle")."</td>" : "")."
	</tr>");
	}
	echo("
</table>");
} else {
	echo("
<strong>No new requested tracks.</strong>");
}
?>