<?php
require_once('pre.php');
Output::set_title("Censored Tracks");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
Output::add_script(SITE_LINK_REL."js/bootstrap-popover.js");

echo("<script>
	$(function () {
		$('.track-info').popover({
			'html': true, 
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

if($_REQUEST["censor"]) {
	if(!Session::is_group_user("Music Admin")) {
		echo AlertMessage::basic("error","You are trying to censor a track, but you do not have the required privileges!","Error!");
	} else {
		$track = Tracks::get_by_id($_REQUEST["censor"]);
		if($track) {
			$track->set_censored(true);
			$track->set_flagged(false);
			$track->save();
			echo AlertMessage::basic("success","The track ".$track->get_title()." by ".$track->get_artists_str()." has been censored.","Track censored!");
		}
		else echo AlertMessage::basic("error","The track you are trying to censor doesn't exist!","Error!");
	}
}

if($_REQUEST["uncensor"]) {
	if(!Session::is_group_user("Music Admin")) {
		echo AlertMessage::basic("error","You are trying to uncensor a track, but you do not have the required privileges!","Error!");
	} else {
		$track = Tracks::get_by_id($_REQUEST["uncensor"]);
		if($track) {
			$track->set_censored(false);
			$track->save();
			echo AlertMessage::basic("success","The track ".$track->get_title()." by ".$track->get_artists_str()." has been uncensored.","Track uncensored!");
		}
		else echo AlertMessage::basic("error","The track you are trying to uncensor doesn't exist!","Error!");
	}
}

if($_REQUEST["unflag"]) {
	if(!Session::is_group_user("Music Admin")) {
		echo AlertMessage::basic("error","You are trying to unflag a track, but you do not have the required privileges!","Error!");
	} else {
		$track = Tracks::get_by_id($_REQUEST["unflag"]);
		if($track) {
			$track->set_flagged(false);
			$track->save();
			echo AlertMessage::basic("success","The track ".$track->get_title()." by ".$track->get_artists_str()." has been unflagged.","Track unflagged!");
		}
		else echo AlertMessage::basic("error","The track you are trying to unflag doesn't exist!","Error!");
	}
}

echo("<h2>Flagged for Censorship</h2>");
echo("<strong>To flag a track for censorship, search for it in the music library, click the <i class=\"icon-info-sign\"></i> and click the \"Flag for censorship\" button.</strong>");
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
			<a href=\"".SITE_LINK_REL."music/detail/".$flag->get_id()."\" class=\"track-info\">
				<i class=\"icon-info-sign\"></i>
			</a>
			<div class=\"hover-info\">
				<strong>Artist:</strong> ".$flag->get_artists_str()."<br />
				<strong>Album:</strong> ".$flag->get_album()->get_name()."<br />
				<strong>Year:</strong> ".$flag->get_year()."<br />
				<strong>Length:</strong> ".Time::format_succinct($flag->get_length())."<br />
				<strong>Origin:</strong> ".$flag->get_origin()."<br />
				".($flag->get_reclibid()? "<strong>Reclib ID:</strong> ".$flag->get_reclibid()."<br />" : "")."
				<strong>On Sue:</strong> ".($flag->is_sustainer()? "Yes" : "No")."<br />
			</div>
		</td>
		<td class=\"artist\">".$flag->get_artists_str()."</td>
		<td class=\"title\">".$flag->get_title()."</td>
		".(Session::is_group_user("Music Admin")? "
		<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/censor/?censor=".$flag->get_id()."\" class=\"censor\" title=\"Approve censorship\" rel=\"twipsy\"><i class=\"icon-ok-sign\"></i></td>
		<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/censor/?unflag=".$flag->get_id()."\" class=\"unflag\" title=\"Remove flag\" rel=\"twipsy\"><i class=\"icon-remove-sign\"></td>" : "")."
	</tr>");
	}
	echo("
</table>");
} else {
	echo("<h3>No tracks currently flagged for censorship.</h3>");
}

$limit = (isset($_REQUEST['n']))? $_REQUEST['n'] : 10;
$page = ($_REQUEST['p']? $_REQUEST['p'] : 1);
$num_of_censored = Tracks::count_censored();

if($censored = Tracks::get_censored($limit,(($page-1)*$limit))) {

	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->querystring = $query;
	$pages->mid_range = 5;
	$pages->items_total = $num_of_censored;
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > $num_of_censored)? $num_of_censored : $low + $limit - 1;

	echo("
<h2>Censored Tracks</h2>
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
			<a href=\"".SITE_LINK_REL."music/detail/".$censor->get_id()."\" class=\"track-info\">
				<i class=\"icon-info-sign\"></i>
			</a>
			<div class=\"hover-info\">
				<strong>Artist:</strong> ".$censor->get_artists_str()."<br />
				<strong>Album:</strong> ".$censor->get_album()->get_name()."<br />
				<strong>Year:</strong> ".$censor->get_year()."<br />
				<strong>Length:</strong> ".Time::format_succinct($censor->get_length())."<br />
				<strong>Origin:</strong> ".$censor->get_origin()."<br />
				".($censor->get_reclibid()? "<strong>Reclib ID:</strong> ".$censor->get_reclibid()."<br />" : "")."
				<strong>On Sue:</strong> ".($censor->is_sustainer()? "Yes" : "No")."<br />
			</div>
		</td>
		<td class=\"artist\">".$censor->get_artists_str()."</td>
		<td class=\"title\">".$censor->get_title()."</td>
		".(Session::is_group_user("Music Admin")? "<td class=\"icon\"><a href=\"".SITE_LINK_REL."music/censor/?uncensor=".$censor->get_id()."\" class=\"uncensor\" title=\"Uncensor this track\" rel=\"twipsy\"><i class=\"icon-remove-sign\"></i></td>" : "")."
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