<?php
require_once('pre.php');
Output::set_title("Track Detail");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
Output::add_script(SITE_LINK_REL."js/bootstrap-alerts.js");
Output::add_script(SITE_LINK_REL."js/jquery.jplayer.min.js");
Output::add_stylesheet(SITE_LINK_REL."css/jplayer.css");

MainTemplate::set_subtitle("View and edit track metadata");

if(!isset($_GET['id'])) {
	exit("<h2>No track specified</h2><h3>You must access this via another page, to get metadata for a specified track.</h3>");
}

if(!$track = Tracks::get($_GET["id"])) {
	exit("<h2>Invalid track ID</h2><h3>If you got to this page via a link from somewhere else on the site, there may be a bug.  A bug you should bug the techies about!</h3>");
}

echo("
	<script>
		$(function () {
			$('.track-meta-form').submit(function(event) {
				event.preventDefault();
				var el = $(this);
				el.find('.help-inline').remove();
				submit = $(this).find('input[type=\"submit\"]');
				submit.button('loading');
				$.post('".SITE_LINK_REL."ajax/meta-update', $(this).serialize(), function(data) {
					if(data == \"success\") { 
						submit.button('reset');
						location.reload();
					} else {
						$('h2').after('".AlertMessage::basic("error","'+data+'","Error!")."');
						$('.alert-message').show('fast').alert();
						submit.button('reset');
					}
				})
			});
			$('.track-detail-form').submit(function(event) {
				event.preventDefault();
				var el = $(this);
				el.find('.help-inline').remove();
				submit = $(this).find('input[type=\"submit\"]');
				submit.button('loading');
				$.post('".SITE_LINK_REL."ajax/track-detail-update', $(this).serialize(), function(data) {
					if(data == \"success\") { 
						submit.button('reset');
						$('h2').after('".AlertMessage::basic("success","Track details altered. Reloading page...","Success!",false)."');
						setTimeout(function() {
    						$('.alert-message').hide('fast', function(){
        						$(this).remove(); 
           					});},4000);
           				setTimeout(\"location.reload();\",4200);
					} else {
						$('h2').after('".AlertMessage::basic("error","'+data+'","Error!")."');
						$('.alert-message').alert();
						submit.button('reset');
					}
				})
			});
			$('.keyword a').click(function(event) {
				event.preventDefault();
				$.get($(this).attr('href'), function(data) {
					if(data == \"success\") {
						location.reload();
					} else {
						$('h2').after('".AlertMessage::basic("error","'+data+'","Error!")."');
						$('.alert-message').show('fast').alert();
					}
				})
			});

   			$(\"#jquery_jplayer_1\").jPlayer({
				ready: function(event) {
					$(this).jPlayer(\"setMedia\", {
						mp3: \"../../lib/preview/preview.php?id=".$track->get_id()."\"
					});
				},
				supplied: \"mp3\"
			});
			$(\"#jquery_jplayer_1\").bind($.jPlayer.event.loadeddata, function(event) {
				$('.jp-controls li').show();
				$('.jp-controls li.dps-file-loading').hide();
				$('.jp-timestamp-placeholder').hide();
				$('.jp-timestamp').show();
			});
			
			$('.dps-waveform img').load(function(){
				$('.dps-waveform-loading').fadeOut('fast', function(){ $('.dps-waveform').fadeIn(); });
			});

		});
	</script>
	<h2>Edit Track: ".$track->get_id()." <small>Added ".date("d/m/Y H:i",$track->get_import_date())."</small></h2>
	".(Session::is_group_user("Music Admin")? "":AlertMessage::basic("info","You can't edit the details of this track, because you aren't a Music Admin.","Notice:")));
	if($_REQUEST["flag"]) {
		if($track->is_flagged()) {
			$track->set_flagged(false);
			$track->save();
			echo(AlertMessage::basic("success","This track has been unflagged for censorship.","Track unflagged!"));
		} else {
			$track->set_flagged(true);
			$track->save();
			echo(AlertMessage::basic("success","This track has been flagged for censorship and will be reviewed in due course.","Track flagged!"));
		}
	}
	echo("

       <div id=\"jquery_jplayer_1\" class=\"jp-jplayer\"></div>
        
        <div id=\"jp_container_1\" class=\"jp-audio\">
            <div class=\"jp-type-single\">
                <div class=\"jp-gui jp-interface\">
                    <ul class=\"jp-controls\">
                        <li class=\"dps-file-loading\"><img src=\"".SITE_LINK_REL."images/ajax-loader.gif\"></li>
                        <li><a href=\"javascript:;\" class=\"jp-play\" tabindex=\"1\">play</a></li>
                        <li><a href=\"javascript:;\" class=\"jp-pause\" tabindex=\"1\">pause</a></li>
                    </ul>
                    
                    <div class=\"jp-progress-wrap\">
                    <div class=\"jp-progress\">
			   <img class=\"dps-waveform-loading\" src=\"".SITE_LINK_REL."images/ajax-loader.gif\" />
			   <div class=\"dps-waveform\"><img src=\"".SITE_LINK_REL."lib/waveformgen/waveform.php?id=".$track->get_id()."\" /></div>
                        <div class=\"jp-seek-bar\">
                            <div class=\"jp-play-bar\"></div>
                        </div>
                    </div>
			</div>
			<div class=\"jp-timestamp-placeholder\">
			Loading Audio
			</div>
			<div class=\"jp-timestamp\">
                    <div class=\"jp-current-time\"></div> /
                    <div class=\"jp-duration\"></div>    </div>               
                </div>

            </div>
        </div>

	<div class=\"row\">
		<div class=\"span6\">
			<form class=\"track-detail-form form-horizontal\" action=\"\" method=\"post\">
				<fieldset>
					<input type=\"hidden\" name=\"id\" value=\"".$track->get_id()."\">
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"title\">Title</label>
						<div class=\"controls\">
							<input name=\"title\" class=\"required".$disabled."\" value=\"".$track->get_title()."\">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"artist\">Artists</label>");
						foreach($track->get_artists() as $artist) {
							echo("
						<div class=\"controls\">
							<input name=\"artist[]\" class=\"required".$disabled."\" value=\"".$artist->get_name()."\">
						</div>");
						}
					echo("
						<div class=\"controls\">
							<input name=\"new_artist[]\" class=\"click-clear".$disabled."\" placeholder=\"Add new artist...\">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"album\">Album</label>
						<div class=\"controls\">
							<input name=\"album\" class=\"required".$disabled."\" value=\"".$track->get_album()->get_name()."\">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"year\">Year</label>
						<div class=\"controls\">
							<input name=\"year\" class=\"".$disabled."\" value=\"".$track->get_year()."\">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"length\">Length</label>
						<div class=\"controls\">
							<span class=\"uneditable-input\">".Time::format_succinct($track->get_length())."</span>
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"origin\">Origin</label>
						<div class=\"controls\">
							<input name=\"origin\" class=\"required".$disabled."\" value=\"".$track->get_origin()."\">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"reclibid\">Reclib ID</label>
						<div class=\"controls\">
							<input name=\"reclibid\" class=\"".$disabled."\" value=\"".$track->get_reclibid()."\">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"censored\">Censored</label>
						<div class=\"controls\">
							<input type=\"checkbox\" name=\"censored\" class=\"".$disabled."\" ".($track->is_censored()? "checked" : "").">
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"sustainer\">On Sue</label>
						<div class=\"controls\">
							<input type=\"checkbox\" name=\"sustainer\" class=\"".$disabled."\" ".($track->is_sustainer()? "checked" : "").">
						</div>
					</div>
					<div class=\"control-group\">
						<div class=\"controls\">
							<input type=\"submit\" class=\"btn btn-primary\" value=\"Save\">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		<div class=\"span3\">
			<form class=\"track-meta-form form-stacked\" action=\"".SITE_LINK_REL."ajax/meta-update\" method=\"POST\">
				<fieldset>
					<input type=\"hidden\" name=\"id\" value=\"".$track->get_id()."\">
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"notes\">Notes</label>
						<div class=\"controls\">
							<textarea name=\"notes\" class=\"".$disabled."\">".$track->get_notes()."</textarea>
						</div>
					</div>
					<div class=\"control-group\">
						<label class=\"control-label\" for=\"keyword\">Keywords</label>");
						foreach($track->get_keywords() as $keyword) {
							echo("
						<div class=\"controls\">
							<div class=\"keyword\">
								<a href=\"".SITE_LINK_REL."ajax/del-keywords?track_id=".$track->get_id()."&keyword=".$keyword->get_text()."\" rel=\"twipsy\" title=\"Delete this keyword\"><i class=\"icon-remove-sign\"></i></a>
							</div>
							<span class=\"uneditable-input\">".$keyword->get_text()."</span>
						</div>");
						}
					echo("
						<div class=\"input-prepend\">
							<span class=\"add-on\"><i class=\"icon-tag\"></i></span>
							<input name=\"new_keyword[]\" style=\"width: 183px\" class=\"click-clear".$disabled."\" placeholder=\"Add new keyword...\">
						</div>
					</div>
					<div class=\"control-group\">
						<div class=\"controls\">
							<input type=\"submit\" class=\"btn btn-primary\" value=\"Save\">
						</div>
					</div>
				</fieldset>
			</form>
			<form class=\"flag-track-form form-stacked\" action=\"\" method=\"POST\">
				<fieldset>
					<div class=\"control-group\">
						<div class=\"controls\">
							<input type=\"submit\" name=\"flag\" class=\"btn btn-danger".($track->is_flagged()? " active" : "")."\" value=\"Flag for censorship\">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
");
?>
