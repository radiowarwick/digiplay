<?php

Output::require_group("Censor");
Output::set_title("Censored Tracks");

echo("<script>
	$(function () {
		$('.track-info').popover({
			'html': true, 
			'trigger': 'hover',
			'title': function() { 
				return($(this).parent().parent().find('.title').html())
			},
			'content': function() {
				return($(this).parent().find('.hover-info').html());
			}
		});
	});
</script>");

MainTemplate::set_subtitle("Heard some naughty words? Censor tracks so their playout is restricted");

if(isset($_REQUEST["censor"]) && is_numeric($_REQUEST["censor"])) {
	if(!Session::is_group_user("Censor")) trigger_error("You are trying to censor a track, but you do not have the required privileges!");
	else {
		$track = Tracks::get_by_id($_REQUEST["censor"]);
		if($track) {
			$track->set_censored(true);
			$track->set_flagged(false);
			$track->save();
			echo Bootstrap::alert("success","The track ".$track->get_title()." by ".$track->get_artists_str()." has been censored.","Track censored!");
		}
		else trigger_error("The track you are trying to censor doesn't exist!");
	}
}

if(isset($_REQUEST["uncensor"]) && is_numeric($_REQUEST["uncensor"])) {
	if(!Session::is_group_user("Censor")) trigger_error("You are trying to uncensor a track, but you do not have the required privileges!");
	else {
		$track = Tracks::get_by_id($_REQUEST["uncensor"]);
		if($track) {
			$track->set_censored(false);
			$track->save();
			echo Bootstrap::alert("success","The track ".$track->get_title()." by ".$track->get_artists_str()." has been uncensored.","Track uncensored!");
		}
		else trigger_error("The track you are trying to uncensor doesn't exist!");
	}
}

if(isset($_REQUEST["unflag"]) && is_numeric($_REQUEST["unflag"])) {
	if(!Session::is_group_user("Censor")) {
		echo Bootstrap::alert_message_basic("error","You are trying to unflag a track, but you do not have the required privileges!","Error!");
	} else {
		$track = Tracks::get_by_id($_REQUEST["unflag"]);
		if($track) {
			$track->set_flagged(false);
			$track->save();
			echo Bootstrap::alert_message_basic("success","The track ".$track->get_title()." by ".$track->get_artists_str()." has been unflagged.","Track unflagged!");
		}
		else trigger_error("The track you are trying to unflag doesn't exist!");
	}
}

echo("<h3>Flagged for Censorship</h3>");
echo("<strong>To flag a track for censorship, search for it in the music library, click the ".Bootstrap::fontawesome("info-circle")." and click the \"Flag for censorship\" button.</strong>");
if($flagged = Tracks::get_flagged()) {
	echo("
<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"></th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			".(Session::is_group_user("Music Admin")? "
			<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($flagged as $flag) {
		echo("
	<tr id=\"".$flag->get_id()."\">
		<td class=\"icon\">
			<a href=\"".LINK_ABS."music/detail/".$flag->get_id()."\" class=\"track-info\">
				".Bootstrap::fontawesome("info-circle")."
			</a>
			<div class=\"hover-info\">
				<strong>Artist:</strong> ".$flag->get_artists_str()."<br />
				<strong>Album:</strong> ".$flag->get_album()->get_name()."<br />
				<strong>Year:</strong> ".$flag->get_year()."<br />
				<strong>Length:</strong> ".Time::format_succinct($flag->get_length())."<br />
				<strong>Origin:</strong> ".$flag->get_origin()."<br />
				".($flag->get_reclibid()? "<strong>Reclib ID:</strong> ".$flag->get_reclibid()."<br />" : "")."
			</div>
		</td>
		<td class=\"artist\">".$flag->get_artists_str()."</td>
		<td class=\"title\">".$flag->get_title()."</td>
		".(Session::is_group_user("Music Admin")? "
		<td class=\"icon\"><a href=\"".LINK_ABS."music/censor/?censor=".$flag->get_id()."\" class=\"censor\" title=\"Approve censorship\" rel=\"twipsy\">".Bootstrap::fontawesome("check-circle")."</td>
		<td class=\"icon\"><a href=\"".LINK_ABS."music/censor/?unflag=".$flag->get_id()."\" class=\"unflag\" title=\"Remove flag\" rel=\"twipsy\">".Bootstrap::fontawesome("times-circle")."</td>" : "")."
	</tr>");
	}
	echo("
</table>");
} else {
	echo("<h4>No tracks currently flagged for censorship.</h4>");
}

$limit = (isset($_REQUEST['n']))? $_REQUEST['n'] : 10;
$page = (isset($_REQUEST['p'])? $_REQUEST['p'] : 1);
$num_of_censored = Tracks::count_censored();

if($censored = Tracks::get_censored($limit,(($page-1)*$limit))) {

	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->querystring = NULL;
	$pages->mid_range = 5;
	$pages->items_total = $num_of_censored;
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > $num_of_censored)? $num_of_censored : $low + $limit - 1;

	echo("
<h3>Censored Tracks</h3>
<table class=\"table table-striped\" cellspacing=\"0\">
	<thead>
		<tr>
			<th class=\"icon\"></th>
			<th class=\"artist\">Artist</th>
			<th class=\"title\">Title</th>
			".(Session::is_group_user("Music Admin")? "
			<th class=\"icon\"></th>" : "")."
		</tr>
	</thead>");
	foreach($censored as $censor) {
		echo("
	<tr id=\"".$censor->get_id()."\">
		<td class=\"icon\">
			<a href=\"".LINK_ABS."music/detail/".$censor->get_id()."\" class=\"track-info\">
				".Bootstrap::fontawesome("info-circle")."
			</a>
			<div class=\"hover-info\">
				<strong>Artist:</strong> ".$censor->get_artists_str()."<br />
				<strong>Album:</strong> ".$censor->get_album()->get_name()."<br />
				<strong>Year:</strong> ".$censor->get_year()."<br />
				<strong>Length:</strong> ".Time::format_succinct($censor->get_length())."<br />
				<strong>Origin:</strong> ".$censor->get_origin()."<br />
				".($censor->get_reclibid()? "<strong>Reclib ID:</strong> ".$censor->get_reclibid()."<br />" : "")."
			</div>
		</td>
		<td class=\"artist\">".$censor->get_artists_str()."</td>
		<td class=\"title\">".$censor->get_title()."</td>
		".(Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"".LINK_ABS."music/censor/?uncensor=".$censor->get_id()."\" class=\"uncensor\" title=\"Uncensor this track\" rel=\"twipsy\">".Bootstrap::fontawesome("times-circle")."</td>" : "")."
	</tr>");
	}
	echo("
</table>");
	echo($pages->return);
} else {
	echo("
<strong>No censored tracks.</strong>");
}
?>