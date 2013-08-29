<?php
Output::set_title("Studio Management");
Output::add_script(LINK_ABS."js/moment.min.js");

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
			var key;
			".(isset($_REQUEST["key"])? "key = 'key=".$_REQUEST['key']."&';" : "")."
		</script>
		<div class=\"wrap\">
			<nav class=\"navbar navbar-default navbar-fixed-top\" id=\"header\">
				<script> $(function() { setInterval(function() { $('#now-next').load('functions.php?'+key+'action=now-next') }, 60000); $('#now-next').load('functions.php?'+key+'action=now-next'); }); </script>
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
									$(function() { setInterval(function() { $('#info-content').load('functions.php?'+key+'action=info-content') }, 60000) })
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
												.load('functions.php?'+key+'action=search&'+$('#search-form').serialize()) 
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
							<div class=\"tab-pane\" id=\"messages\">
								<script>
									$(function() { 
										setInterval(function() { $('#message-list').load('functions.php?'+key+'action=messages') }, 30000); $('#message-list').load('functions.php?'+key+'action=messages');
										$(document).on('click', '#message-list tr', function() { 
											$('#message-content h4').html($(this).find('.subject').html());
											$('#message-content iframe').attr('src', 'functions.php?'+key+'action=message&id='+$(this).attr('id')); 
											$(this).find('span').removeClass('glyphicon-envelope');
										});
									})
								</script>
								<div id=\"message-list\">
									No messages currently available.
								</div>
								<div id=\"message-content\">
									<div class=\"panel panel-default\">
										<div class=\"panel-heading\"><h4>&nbsp;</h4></div>
										<div class=\"panel-body\"><iframe></iframe></div>
									</div>
								</div>
							</div>
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
				<script>
					$(function() { 
						setInterval(function () { 
							$('#clock .time').html(moment().format('h:mm:ss a'));
							$('#clock .date').html(moment().format('dddd Do MMMM YYYY'));
						}, 1000);
					}); 
				</script>
				<img src=\"".LINK_ABS."img/footer_logo.png\" alt=\"RaW 1251AM\" id=\"logo\" />
				<div class=\"col-sm-3 navbar-brand\">
					<div id=\"clock\">
						<span class=\"time\">00:00:00</span><br />
						<span class=\"date\">1st January 1970</span>
					</div>
				</div>
				<div class=\"col-sm-3 pull-right\"><a href=\"#\" data-toggle=\"modal\" data-target=\"login-modal\" class=\"btn btn-primary btn-lg btn-block\">Log In</a></div>

				<div class=\"col-sm-3 pull-right\" id=\"contact\">
					".Bootstrap::glyphicon("phone")." ".Config::get_param("contact_sms")."<br />
					".Bootstrap::glyphicon("earphone")." ".Config::get_param("contact_phone")."<br />
					".Bootstrap::glyphicon("envelope")." ".Config::get_param("contact_email")."
				</div>
			</nav>
		</div>
	");

?>