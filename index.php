<?php
require_once('pre.php');

$refer = preg_replace('/\&/', '?', $_REQUEST["refer"], 1);
if(isset($_REQUEST['refer']) && Session::is_user()) header("Location: ".SITE_LINK_REL.$refer);
MainTemplate::set_feature_image(SITE_LINK_REL."img/homepage.jpg");

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
				<div class=\"col-span-8\">
					<h1>Digiplay <small>by Radio Warwick</small></h1>
					<p>A magical software suite run by miniature elves, which is amazing because elves are already very small by their nature.</p>
				</div>
				<div class=\"col-span-4\">
				".((Session::is_user())? "
					<h2>Common Tasks</h2>
					<a href=\"upload\" class=\"btn btn-primary btn-large btn-block\">Upload Audio &raquo;</a>
					<a href=\"playlists\" class=\"btn btn-primary btn-large btn-block\">Edit Playlists &raquo;</a>
					<a href=\"sue/schedule\" class=\"btn btn-primary btn-large btn-block\">Schedule Prerecorded Content &raquo;</a>
				":"
					<h2>Log In".(isset($_REQUEST['refer'])? "<small class=\"error\" style=\"font-size: 0.7em\"> to access restricted content</small>" : "")."</h2><br />
					<form class=\"form-signin\" action=\"ajax/login.php\" method=\"post\">
						<fieldset>
							<input id=\"username\" name=\"username\" type=\"text\" class=\"input-block-level\" placeholder=\"Username\">
							<input id=\"password\" name=\"password\" type=\"password\" class=\"input-block-level\" placeholder=\"Password\">
							<input type=\"submit\" class=\"btn btn-large btn-primary btn-block\" id=\"submit\" name=\"submit\" value=\"Log In\">
						</fieldset>
					</form>
				")."
				</div>
			</div>
		";
		echo("<div class=\"row\">
			<div class=\"col-span-4\">
				<h2>Music Library</h2>
				<dl>
					<dt>Tracks Stored</dt>
					<dd>".number_format(Tracks::get_total_tracks())."</dd>
					<dt>Length of Tracks</dt>
					<dd>".Time::format_pretty(Tracks::get_total_length())."</dd>
					<dt>Playlisted Tracks</dt>
					<dd>".count(Tracks::get_playlisted())."</dd>
				</dl>
			</div>
			<div class=\"col-span-4\">
				<h2>Sustainer Service</h2>
				<dl>
					<dt>Tracks on Sustainer</dt>
					<dd>".Sustainer::get_total_tracks()."</dd>
					<dt>Length of Sustainer Playlist</dt>
					<dd>".Sustainer::get_total_length_formatted()."</dd>
				</dl>
				<a class=\"btn\" href=\"".SITE_LINK_REL."sue/\"><span class=\"glyphicon glyphicon-headphones\"></span> Now playing</a>
				<a class=\"btn\" href=\"".SITE_LINK_REL."playlists/0\"><span class=\"glyphicon glyphicon-list\"></span> View playlist</a>
			</div>
			<div class=\"col-span-4\">
				<h2>Newest Tracks</h2>");
				$tracks = Tracks::get_newest(4);
				echo("<table class=\"table table-striped table-hover table-condensed\" cellspacing=\"0\">");
				foreach($tracks as $track) {
					$artists = Artists::get_by_audio_id($track->get_id());
					$artist_str = "";
					foreach($artists as $artist) $artist_str .= $artist->get_name()."; ";
					$artist_str = substr($artist_str,0,-2);
					echo("
					<tr>
						<td class=\"icon\">
							<a href=\"".SITE_LINK_REL."music/detail/".$track->get_id()."\" class=\"track-info\">
								<i class=\"glyphicon glyphicon-info-sign\"></i>
							</a>
						</td>
						<td class=\"title\">".$track->get_title()." by ".$artist_str."</td>
					</tr>");
				}
				echo("
				</table>
				<a class=\"btn\" href=\"".SITE_LINK_REL."music/\"><span class=\"glyphicon glyphicon-chevron-right\"></span> More</a>
			</div>
		</div>");

	MainTemplate::set_feature_html($feature);

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
