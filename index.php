<?php
require_once('pre.php');

$masthead = "<h1>Digiplay, from Radio Warwick</h1><p>A magical software suite run by miniature elves, which is amazing because elves are already very small by their nature.</p>";
MainTemplate::set_masthead($masthead);

function total_track_time($time_arr) {
	$time_str = ($time_arr["days"])? $time_arr["days"]." days, " : "";
	$time_str .= ($time_arr["hours"])? $time_arr["hours"]." hours, " : "";
	$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]." minutes, " : "";
	$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]." seconds" : "";
	return $time_str;
}
$refer = preg_replace('/\&/', '?', $_REQUEST["refer"], 1);
if(isset($_REQUEST['refer']) && Session::is_user()) header("Location: ".SITE_LINK_ABS.$refer);
?>
<script type="text/javascript">
$(function () {
	$('#username').focus();
	$('.login-form').submit(function(event) {
		event.preventDefault();
		$('#submit').button('loading');
		$('.help-inline').remove();
		$.post('ajax/login', $(this).serialize(), function(data) {
			if(data == "success") { 
				location.reload()
			} else {
				$('#submit').after('<span class="help-inline">'+data+'</span>');
				$('#submit').button('reset');
			}
		})
	});
});
</script>
<?php
	MainTemplate::set_summary("
		<div class=\"row\">
			<div class=\"span4\">
				<h2>Music Library</h2>
				<dl>
					<dt>Tracks Stored</dt>
					<dd>".number_format(Tracks::get_total_tracks())."</dd>
					<dt>Length of Tracks</dt>
					<dd>".total_track_time(Time::seconds_to_dhms(Tracks::get_total_length()))."</dd>
					<dt>Playlisted Tracks</dt>
					<dd>".count(Tracks::get_playlisted())."</dd>
				</dl>
			</div>
			<div class=\"span4\">
				<h2>Sustainer Service</h2>
				<dl>
					<dt>Tracks on Sustainer</dt>
					<dd>".Sustainer::get_total_tracks()."</dd>
					<dt>Length of Sustainer Playlist</dt>
					<dd>".Sustainer::get_total_length_formatted()."</dd>
				</dl>
			</div>
			<div class=\"span4\">
			".((Session::is_user())? "
				<h2>Common Tasks</h2>
				<a href=\"upload\" class=\"btn-primary btn\">Upload Audio &raquo;</a>
				<a href=\"playlists\" class=\"btn-primary btn\">Edit Playlists &raquo;</a>
				<a href=\"sue/schedule\" class=\"btn-primary btn\">Schedule Prerecorded Content &raquo;</a>
			":"
				<h2>Log In".(isset($_REQUEST['refer'])? "<small class=\"error\" style=\"font-size: 0.7em\"> to access restricted content</small>" : "")."</h2><br />
				<form class=\"login-form form-horizontal\" action=\"ajax/login\" method=\"post\">
					<fieldset>
						<div class=\"control-group".(isset($_REQUEST['refer'])? " error" : "")."\">
							<label for=\"username\" class=\"control-label\">Username</label>
							<div class=\"controls\">
								<input id=\"username\" name=\"username\" type=\"text\" class=\"required\">
							</div>
						</div>
						<div class=\"control-group".(isset($_REQUEST['refer'])? " error" : "")."\">
							<label for=\"password\" class=\"control-label\">Password</label>
							<div class=\"controls\">
								<input id=\"password\" name=\"password\" type=\"password\" class=\"required\">
							</div>
						</div>
						<div class=\"control-group\">
							<div class=\"controls\">
								<input type=\"submit\" class=\"btn btn-primary\" id=\"submit\" name=\"submit\" value=\"Log In\">
							</div>
						</div>
					</fieldset>
				</form>
			")."
			</div>
		</div>");

	if(Session::is_user()) {
	Output::set_title("Welcome, ".Session::get_first_name());
	MainTemplate::set_subtitle("Enjoying your day?");
	$lastlogin = Session::get_lastlogin();
	if($lastlogin) echo("<h4>You last logged in: ".strftime("%A %e %B %G %H:%M", $lastlogin)."</h4>");
	else echo ("<h4>You've never logged in before! Welcome to the Digiplay Web Management System.</h4>");
	}; 
?>
