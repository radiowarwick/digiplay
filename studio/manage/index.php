<?php
Output::set_title("Studio Management");

if(isset($_REQUEST["key"])) {
	$location = Config::get_location_from_key($_REQUEST["key"]);
	Output::add_stylesheet(LINK_ABS."css/studio.css");
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
		<div class=\"wrap\">
			<nav class=\"navbar navbar-default navbar-fixed-top\" id=\"header\">
				<script> $(function() { setInterval(function() { $('#now-next').load('functions.php?action=now-next', post_data) }, 60000); $('#now-next').load('functions.php?action=now-next', post_data); }); </script>
				<div id=\"now-next\">
					<div class=\"col-sm-6 navbar-brand\">On now: <span id=\"on-now\"></span></div>
					<div class=\"col-sm-6 navbar-brand\">On next: <span id=\"on-next\"></span></div>
				</div>
			</nav>
			<div class=\"container\">
				<div class=\"row\" id=\"main-panel\">
					<div class=\"col-md-7\" id=\"left-panel\">
						<ul class=\"nav nav-tabs nav-justified\" id=\"tabs\">
							<li class=\"active\"><a href=\"#info\" data-toggle=\"tab\">".Bootstrap::glyphicon("info-sign")." Info</a></li>
							<li><a href=\"#search\" data-toggle=\"tab\">".Bootstrap::glyphicon("music")." Music</a></li>
							<li><a href=\"#messages\" data-toggle=\"tab\">".Bootstrap::glyphicon("envelope")." Messages</a></li>
							<li><a href=\"#playlist\" data-toggle=\"tab\">".Bootstrap::glyphicon("th-list")." Playlist</a></li>
							<li><a href=\"#files\" data-toggle=\"tab\">".Bootstrap::glyphicon("folder-open")." Files</a></li>
							<li><a href=\"#logging\" data-toggle=\"tab\">".Bootstrap::glyphicon("pencil")." Logging</a></li>
						</ul>
						<p />
						<div class=\"tab-content\" id=\"left-panel-content\">
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
									$(function() { 
										$('#search-form').submit(function(e) { 
											e.preventDefault(); 
											$('#search-results').html('<h1 id=\"searching\">".Bootstrap::glyphicon("refresh rotate")." Searching...</h1>')
												.load('functions.php?action=search&'+$('#search-form').serialize(), post_data) 
										})
									})
								</script>
								<form class=\"form-horizontal\" id=\"search-form\">
									<div class=\"form-group\">
										<div class=\"col-xs-10\" id=\"search-text\" >
											<input type=\"text\" class=\"form-control\" name=\"search-text\" placeholder=\"Search...\">
										</div>
										<div class=\"col-xs-2\" id=\"search-button\">
											<button type=\"submit\" class=\"btn btn-primary btn-block\">Search</button>
										</div>
									</div>
										<div class=\"col-xs-12\" id=\"search-options\">
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
								</form>
								<div id=\"search-results\">
									<h3>Enter something to search for in the box above.</h3>
								</div>
							</div>
							<div class=\"tab-pane\" id=\"messages\">3</div>
							<div class=\"tab-pane\" id=\"playlist\">4</div>
							<div class=\"tab-pane\" id=\"files\">5</div>
							<div class=\"tab-pane\" id=\"logging\">6</div>
						</div>
					</div>
					<div class=\"col-md-5\">
						<h3 id=\"showplan-title\">Showplan</h3>
						<div class=\"list-group\" id=\"showplan\">
							<a href=\"#\" class=\"list-group-item active\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
							<a href=\"#\" class=\"list-group-item\">
	    						<h4 class=\"list-group-item-heading\">List group item heading</h4>
	    						<p class=\"list-group-item-text\">...</p>
							</a>
						</div>
					</div>
				</div>
			</div>
			<nav class=\"navbar navbar-default navbar-fixed-bottom\" id=\"footer\">
				<script> $(function() {  }); </script>
				<div class=\"col-sm-3\">
					<img src=\"".LINK_ABS."img/footer_logo.png\" alt=\"RaW 1251AM\" />
				</div>
				<div class=\"col-sm-6 navbar-brand\">14:24:46</span></div>
				<div class=\"col-sm-3 pull-right\"><a href=\"#\" data-toggle=\"modal\" data-target=\"login-modal\" class=\"btn btn-primary btn-lg btn-block\">Log In</a></div>
			</nav>
		</div>
	");

?>