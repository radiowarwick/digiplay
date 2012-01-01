<?php
require_once('pre.php');
Output::set_title("Track Detail");
Output::add_stylesheet(SITE_LINK_REL."css/music.css");
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
						submit.after('<span class=\"help-inline\">'+data+'</span>');
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
						$(this).after('<span class=\"help-inline\">'+data+'</span>');
					}
				})
			})
		});
	</script>
	<h2>Edit Track: ".$track->get_id()." <small>Added ".date("d/m/Y H:i",$track->get_import_date())."</small></h2>
	<div class=\"row\">
		<div class=\"span7\">
			<form class=\"track-detail-form\" action=\"\" method=\"post\">
				<fieldset>
					<div class=\"clearfix\">
						<label for=\"title\">Title</label>
						<div class=\"input\">
							<input name=\"title\" class=\"required".$disabled."\" value=\"".$track->get_title()."\">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"artist\">Artists</label>");
						foreach($track->get_artists() as $artist) {
							echo("
						<div class=\"input\">
							<input name=\"artist\" class=\"required".$disabled."\" value=\"".$artist->get_name()."\">
						</div>");
						}
					echo("
						<div class=\"input\">
							<input name=\"new_artist[]\" class=\"click-clear".$disabled."\" placeholder=\"Add new artist...\">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"album\">Album</label>
						<div class=\"input\">
							<input name=\"album\" class=\"required".$disabled."\" value=\"".$track->get_album()->get_name()."\">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"year\">Year</label>
						<div class=\"input\">
							<input name=\"year\" class=\"".$disabled."\" value=\"".$track->get_year()."\">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"length\">Length</label>
						<div class=\"input\">
							<span class=\"uneditable-input\">".$track->get_length_formatted()."</span>
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"origin\">Origin</label>
						<div class=\"input\">
							<input name=\"origin\" class=\"required".$disabled."\" value=\"".$track->get_origin()."\">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"reclibid\">Reclib ID</label>
						<div class=\"input\">
							<input name=\"reclibid\" class=\"".$disabled."\" value=\"".$track->get_reclibid()."\">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"censored\">Censored</label>
						<div class=\"input\">
							<input type=\"checkbox\" name=\"censored\" class=\"".$disabled."\" ".($track->is_censored()? "checked" : "").">
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"sustainer\">On Sue</label>
						<div class=\"input\">
							<input type=\"checkbox\" name=\"sustainer\" class=\"".$disabled."\" ".($track->is_sustainer()? "checked" : "").">
						</div>
					</div>
					<div class=\"clearfix\">
						<div class=\"input\">
							<input type=\"submit\" class=\"btn primary\" value=\"Save\">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		<div class=\"span5\">
			<form class=\"track-meta-form form-stacked\" action=\"".SITE_LINK_REL."ajax/meta-update\" method=\"POST\">
				<fieldset>
					<input type=\"hidden\" name=\"id\" value=\"".$track->get_id()."\">
					<div class=\"clearfix\">
						<label for=\"notes\">Notes</label>
						<div class=\"input\">
							<textarea name=\"notes\" class=\"".$disabled."\">".$track->get_notes()."</textarea>
						</div>
					</div>
					<div class=\"clearfix\">
						<label for=\"keyword\">Keywords</label>");
						foreach($track->get_keywords() as $keyword) {
							echo("
						<div class=\"input\">
							<div class=\"keyword\">
								<a href=\"".SITE_LINK_REL."ajax/del-keywords?track_id=".$track->get_id()."&keyword_id=".$keyword->get_id()."\"><img src=\"".SITE_LINK_REL."images/icons/delete.png\"></a>
							</div>
							<span class=\"uneditable-input\">".$keyword->get_text()."</span>
						</div>");
						}
					echo("
						<div class=\"input\">
							<input name=\"new_keyword[]\" class=\"click-clear".$disabled."\" placeholder=\"Add new keyword...\">
						</div>
					</div>
					<div class=\"clearfix\">
						<div class=\"input\">
							<input type=\"submit\" class=\"btn primary\" value=\"Save\">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
");
?>