<?php

if(isset($_REQUEST['refer']) && Session::is_user()) {
	$refer = preg_replace('/\&/', '?', $_REQUEST["refer"], 1);
	header("Location: ".LINK_ABS.$refer);
}

MainTemplate::set_feature_image(LINK_ABS."img/homepage.png");

echo("
		<script type=\"text/javascript\">
		$(function () {
			$('#username').focus();
			$('.form-signin').submit(function(event) {
				event.preventDefault();
				$('#submit').button('loading');
				$('.help-inline').remove();
				$.post('ajax/login.php', $(this).serialize(), function(data) {
					if(data == \"success\") { 
						location.reload()
					} else {
						$('#submit').after('<span class=\"help-inline\">'+data+'</span>');
						$('#submit').button('reset');
					}
				})
			});
		});
		</script>");
$feature = "
			<div class=\"row\">
				<div class=\"col-sm-8\">
					<h1>Digiplay <small>by Radio Warwick</small></h1>
					<p>A magical software suite run by miniature elves, which is amazing because elves are already very small by their nature.</p>
				</div>
				<div class=\"col-sm-4\">
				".((Session::is_user())? "
					<h2>Common Tasks</h2>
					<a href=\"music/upload/\" class=\"btn btn-primary btn-large btn-block\">Upload Audio &raquo;</a>
					<a href=\"playlists/\" class=\"btn btn-primary btn-large btn-block\">Edit Playlists &raquo;</a>
					<a href=\"sustainer/\" class=\"btn btn-primary btn-large btn-block\">Schedule Prerecorded Content &raquo;</a>
					".((Session::is_group_user("Studio Admin"))? "<a href=\"reset.php\" class=\"btn btn-primary btn-large btn-block\">Reset Playout Systems &raquo;</a>" : "")."
				":"
					<form class=\"form-signin\" action=\"ajax/login.php\" method=\"post\">
						<div class=\"form-group".(isset($_REQUEST['refer'])? " has-error" : "")."\">
							<input id=\"username\" name=\"username\" type=\"text\" class=\"form-control input-lg\" placeholder=\"Username\">
						</div>
						<div class=\"form-group".(isset($_REQUEST['refer'])? " has-error" : "")."\">
							<input id=\"password\" name=\"password\" type=\"password\" class=\"form-control input-lg\" placeholder=\"Password\">
						</div>
						<div class=\"form-group\">
							<input type=\"submit\" class=\"btn btn-lg ".(isset($_REQUEST['refer'])? "btn-danger" : "btn-primary")." btn-block\" id=\"submit\" name=\"submit\" value=\"Log In\">
						</div>
					</form>
				")."
				</div>
			</div>
		";
MainTemplate::set_feature_html($feature);
		echo("<div class=\"row\">
			<div class=\"col-sm-4\">
				<h2>Music Library</h2>
				<dl>
					<dt>Tracks Stored</dt>
					<dd>".number_format(Tracks::get_total_tracks())."</dd>
					<dt>Length of Tracks</dt>
					<dd>".Time::format_pretty(Tracks::get_total_length())."</dd>
					<dt>Playlisted Tracks</dt>
					<dd>".(count(Tracks::get_playlisted()) - count(Tracks::get_playlisted(Playlists::get(0))))."</dd>
				</dl>
			</div>
			<div class=\"col-sm-4\">
				<h2>Sustainer Service</h2>
				<dl>
					<dt>Tracks on Sustainer</dt>
					<dd>".Sustainer::get_total_tracks()."</dd>
					<dt>Length of Sustainer Playlist</dt>
					<dd>".Sustainer::get_total_length_formatted()."</dd>
				</dl>
				<a class=\"btn btn-primary btn-block\" href=\"".LINK_ABS."sustainer/\">".Bootstrap::glyphicon("headphones")."Now playing</a>
				<a class=\"btn btn-primary btn-block\" href=\"".LINK_ABS."playlists/detail/0\">".Bootstrap::glyphicon("list")."View playlist</a>
			</div>
			<div class=\"col-sm-4\">
				<h2>Newest Tracks</h2>");
				$tracks = Tracks::get_newest(4);
				echo("<table class=\"table table-striped table-hover table-condensed\" cellspacing=\"0\">");
				foreach($tracks as $track) {
					echo("
					<tr>
						<td class=\"icon\">
							<a href=\"".LINK_ABS."music/detail/".$track->get_id()."\" class=\"track-info\">
								".Bootstrap::glyphicon("info-sign")."
							</a>
						</td>
						<td class=\"title\">".$track->get_title()." by ".$track->get_artists_str()."</td>
					</tr>");
				}
				echo("
				</table>
				<a class=\"btn btn-primary btn-block\" href=\"".LINK_ABS."music/\">".Bootstrap::glyphicon("chevron-right")."More</a>
			</div>
		</div>");

	if(Session::is_user()) {
		$lastlogin = Session::get_lastlogin();
		if($lastlogin) echo("<p class=\"text-success\">You last logged in: ".strftime("%A %e %B %G %H:%M", $lastlogin)."</p>");
		else echo ("<p class=\"text-success\">You've never logged in before! Welcome to the Digiplay Web Management System.</p>");
		echo"<h4>Tracks of the Day:</h4><ul>";
		$tracks = Tracks::get_tracks_of_the_day(3);
		foreach( $tracks as $track ) {
			echo"<li><a href=\"music/detail/".$track->get_id()."\">".$track->get_artists_str()." - ".$track->get_title()."</a></li>";
		}
		echo "</ul>";
	}; 
?>
