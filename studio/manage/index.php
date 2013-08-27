<?php
Output::set_title("Studio Management");

if(isset($_REQUEST["key"])) {
	$location = Config::get_location_from_key($_REQUEST["key"]);
} else {
	if(isset($_REQUEST["location"])) {
		$location = $_REQUEST["location"];
	} else {
		exit("No location specified!");
	}
}

MainTemplate::set_subtitle("Studio ".$location);

echo("
		<script>
			var post_data = [];
			".(isset($_REQUEST["key"])? "post_data['key'] = '".$_REQUEST['key']."';" : "")."
		</script>
		<div class=\"container\">
			<div class=\"row\">
				<div class=\"col-md-8\">
					<ul class=\"nav nav-tabs\" id=\"tabs\">
						<li class=\"active\"><a href=\"#info\" data-toggle=\"tab\">".Bootstrap::glyphicon("info-sign")." Info</a></li>
						<li><a href=\"#search\" data-toggle=\"tab\">".Bootstrap::glyphicon("music")." Music</a></li>
						<li><a href=\"#messages\" data-toggle=\"tab\">".Bootstrap::glyphicon("envelope")." Messages</a></li>
						<li><a href=\"#playlist\" data-toggle=\"tab\">".Bootstrap::glyphicon("th-list")." Playlist</a></li>
						<li><a href=\"#files\" data-toggle=\"tab\">".Bootstrap::glyphicon("folder-open")." Files</a></li>
						<li><a href=\"#logging\" data-toggle=\"tab\">".Bootstrap::glyphicon("pencil")." Logging</a></li>
					</ul>
					<p />
					<div class=\"tab-content\" style=\"height: 100%\">
						<div class=\"tab-pane active\" id=\"info\">
							<script>
								$(function() { setInterval(function() { $('#info-content').load('functions.php?action=info-content', post_data) }, 60000) })
							</script>
							<div id=\"info-content\">
								".Config::get_param("info-content")."
							</div>
						</div>
						<div class=\"tab-pane\" id=\"search\">
							<script>
								$(function() { $('#search-form').submit(function(e) { e.preventDefault(); $('#search-results').load('functions.php?action=search&q='+$('#search-text').val(), post_data) }) })
							</script>
							<form class=\"form-horizontal\" id=\"search-form\">
								<div class=\"form-group\">
									<div class=\"col-xs-10\">
										<input type=\"text\" class=\"form-control\" id=\"search-text\" name=\"search-text\" placeholder=\"Search...\">
									</div>
									<div class=\"col-xs-2\">
										<button type=\"submit\" class=\"btn btn-primary btn-block\">Search</button>
									</div>
								</div>
								<div class=\"form-group\">
									<div class=\"col-xs-12\">
										<label class=\"checkbox-inline\">
											<input type=\"checkbox\" id=\"search-title\" name=\"search-title\" checked>Titles</input>
										</label>
										<label class=\"checkbox-inline\">
											<input type=\"checkbox\" id=\"search-artist\" name=\"search-artist\" checked>Artists</input>
										</label>
										<label class=\"checkbox-inline\">
											<input type=\"checkbox\" id=\"search-album\" name=\"search-album\" checked>Albums</input>
										</label>
									</div>
								</div>
							</form>
							<div id=\"search-results\" style=\"max-height: 100%; overflow: auto;\">
								<h3>Enter something to search for in the box above.</h3>
							</div>
						</div>
						<div class=\"tab-pane\" id=\"messages\">3</div>
						<div class=\"tab-pane\" id=\"playlist\">4</div>
						<div class=\"tab-pane\" id=\"files\">5</div>
						<div class=\"tab-pane\" id=\"logging\">6</div>
					</div>
				</div>
				<div class=\"col-md-4\">
				</div>
			</div>
		</div>
	");

?>