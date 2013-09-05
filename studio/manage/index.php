<?php
Output::set_title("Studio Management");
Output::add_script(LINK_ABS."js/moment.min.js");
Output::add_stylesheet(LINK_ABS."css/studio.css");

if(isset($_REQUEST["key"])) {
	$location = Locations::get_by_key($_REQUEST["key"]);
	$key = $_REQUEST["key"];
} else {
	if(isset($_REQUEST["location"])) {
		$location = Locations::get_by_id($_REQUEST["location"]);
		$key = $location->get_key();
	} else {
		exit("No location specified!");
	}
}


echo("
		<script>
			var key;
			".(isset($key)? "key = 'key=".$key."&';" : "")."

			$(function() {
				window.oncontextmenu = function(event) {
  					event.preventDefault();
    				event.stopPropagation();
    				return false;
				};				
			});
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
							<li ".(Session::is_user()? "" : "class=\"disabled\"")."><a href=\"#playlists\" ".(Session::is_user()? "data-toggle=\"tab\"" : "").">".Bootstrap::glyphicon("th-list")." Playlists</a></li>
							<li ".(Session::is_user()? "": "class=\"disabled\"")."><a href=\"#files\" ".(Session::is_user()? "data-toggle=\"tab\"" : "").">".Bootstrap::glyphicon("folder-open")." Files</a></li>
							<li><a href=\"#logging\" data-toggle=\"tab\">".Bootstrap::glyphicon("pencil")." Logging</a></li>
						</ul>
						<div class=\"tab-content\" id=\"left-panel-content\">
							<div class=\"tab-pane active\" id=\"info\">
								<script>
									$(function() { setInterval(function() { $('#info-content').load('functions.php?'+key+'action=info-content') }, 60000) })
								</script>
								<div id=\"info-content\">
									".Configs::get_system_param("info-content")."
								</div>
							</div>
							<div class=\"tab-pane\" id=\"search\">
								<script>
									$(function() { 
										$('#search-form').submit(function(e) { 
											e.preventDefault(); 
											$('[name=submit-search]').addClass('disabled');
											$('#search-results').html('<h1 class=\"loading\">".Bootstrap::glyphicon("refresh rotate")." Searching...</h1>')
												.load('functions.php?'+key+'action=search&'+$('#search-form').serialize(), function() {
													$('[name=submit-search]').removeClass('disabled');
												}) 
										})
									})
								</script>
								<form class=\"form-horizontal\" id=\"search-form\">
									<div class=\"form-group\">
										<div class=\"col-xs-10\" id=\"search-text\" >
											<input type=\"text\" class=\"form-control\" name=\"search-text\" placeholder=\"Search...\">
										</div>
										<div class=\"col-xs-2\" id=\"search-button\">
											<button type=\"submit\" class=\"btn btn-primary btn-block\" name=\"submit-search\">Search</button>
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
										setInterval(function() { 
											var active_message;
											$.ajax('functions.php?'+key+'action=messages').done(function(data) {
												$('#message-list tr').each(function() { 
													if($(this).hasClass('selected')) active_message = $(this).attr('id');
													$('#message-list').html(data);
													$('#'+active_message).addClass('selected');
												});
											});
										}, 30000); 

										$('#message-list').load('functions.php?'+key+'action=messages');

										$(document).on('click', '#message-list tr', function() { 
											$('#message-list tr').removeClass('selected');
											$(this).addClass('selected');
											$('#message-content h4').html($(this).find('.subject').html());
											$('#message-content iframe').attr('src', 'functions.php?'+key+'action=message&id='+$(this).attr('id')); 
											$(this).find('span').removeClass('glyphicon-envelope');
										});
									})
								</script>
								<div id=\"message-list\">
									<h1 class=\"loading\">".Bootstrap::glyphicon("refresh rotate")."</h1>
								</div>
								<div id=\"message-content\">
									<div class=\"panel panel-default\">
										<div class=\"panel-heading\"><h4>&nbsp;</h4></div>
										<div class=\"panel-body\"><iframe></iframe></div>
									</div>
								</div>
							</div>
							<div class=\"tab-pane\" id=\"playlists\">
								<script>
									$(function() { 
										setInterval(function() {
											var active_playlists = [];
											$.ajax('functions.php?'+key+'action=playlists').done(function(data) {
												$('#playlists-list .panel-collapse').each(function() { 
													if($(this).hasClass('in')) {
														active_playlists.push($(this).attr('id'));
													}
												});
												$('#playlists-list').html(data);
												$.each(active_playlists, function(key, value) {
													$('#'+value).addClass('in');
												});
											});
										}, 60000);

										$('#playlists-list').load('functions.php?'+key+'action=playlists', function() {
											$('#playlists-list .panel-collapse:first').addClass('in');
										});
									});
								</script>
								<div class=\"panel-group\" id=\"playlists-list\">
									<h1 class=\"loading\">".Bootstrap::glyphicon("refresh rotate")."</h1>
								</div>
							</div>
							<div class=\"tab-pane\" id=\"files\">
								<h4>File manager coming soon <br/>(When i've written one)</h4>
							</div>
							<div class=\"tab-pane\" id=\"logging\">
								<script>
									$(function() { 
										$('#logging-form').submit(function(e) { 
											e.preventDefault();
											$('[name=submit-log]').addClass('disabled');
											$('#log').load('functions.php?'+key+'action=log&'+$(this).serialize(), function() {
												$('[name=artist]').val('').focus();
												$('[name=title]').val('');
												$('[name=submit-log]').removeClass('disabled');
											})
										});

										$(function() { setInterval(function() { $('#log').load('functions.php?'+key+'action=log') }, 30000) })
										$('#log').load('functions.php?'+key+'action=log');
									})
								</script>
								<form class=\"form-inline\" id=\"logging-form\">
									<div class=\"form-group\">
										<input type=\"text\" class=\"form-control\" name=\"artist\" placeholder=\"Artist\">
									</div>
									<div class=\"form-group\">
										<input type=\"text\" class=\"form-control\" name=\"title\" placeholder=\"Title\">
									</div>
									<button type=\"submit\" class=\"btn btn-primary\" name=\"submit-log\">Log</button>
								</form>
								<div id=\"log\">
									<h3><h1 class=\"loading\">".Bootstrap::glyphicon("refresh rotate")."</h1></h3>
								</div>
							</div>
						</div>
					</div>
					<div class=\"col-md-5 hidden-sm hidden-xs\">
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
				<img src=\"".LINK_ABS."img/footer_logo.png\" alt=\"RaW 1251AM\" id=\"logo\" class=\"hidden-xs hidden-sm\" />
				<div class=\"col-sm-3 navbar-brand\">
					<div id=\"clock\">
						<span class=\"time\">00:00:00</span><br />
						<span class=\"date\">1st January 1970</span>
					</div>
				</div>
				<div class=\"col-sm-3 pull-right\">
				<a href=\"#\" data-toggle=\"modal\" data-target=\"#logout-modal\" class=\"btn btn-primary btn-lg btn-block\" ".(Session::is_user()? "":"style=\"display:none\"").">Log Out</a>
				<a href=\"#\" data-toggle=\"modal\" data-target=\"#login-modal\" class=\"btn btn-primary btn-lg btn-block\" ".(Session::is_user()? "style=\"display:none\"":"").">Log In</a>
					</div>

				<div class=\"col-sm-3 pull-right\" id=\"contact\">
					".Bootstrap::glyphicon("phone")." ".Configs::get_system_param("contact_sms")."<br />
					".Bootstrap::glyphicon("earphone")." ".Configs::get_system_param("contact_phone")."<br />
					".Bootstrap::glyphicon("envelope")." ".Configs::get_system_param("contact_email")."
				</div>
			</nav>
			<script>
				$(function() {
					$('#yes-login').click(function(e) {
						e.preventDefault();
						$(this).button('loading');
						$('#username').parent().removeClass('has-error');
						$('#password').parent().removeClass('has-error');
						$('#login-modal').find('small').remove();
						$('#login-modal').find('h1').find('span').removeClass('glyphicon-play-circle').addClass('glyphicon-refresh rotate');
						$.ajax({
							url: 'functions.php?'+key+'action=login',
							data: 'username='+$('#username').val()+'&password='+$('#password').val(),
							type: 'POST',
							dataType: 'json'
						}).done(function(data) {
							$('#login-modal').find('h1').find('span').removeClass('glyphicon-refresh rotate').addClass('glyphicon-play-circle');
							if(data.response == 'success') {
								$('li.disabled').removeClass('disabled').find('a').attr('data-toggle','tab');
								$('[data-target=#logout-modal]').show();
								$('[data-target=#login-modal]').hide();
								$('#login-modal').modal('hide');
								$('#yes-login').button('reset');
							} else {
								$('#login-modal').find('h1').append(' <small>invalid username or password</small>');
								$('#username').parent().addClass('has-error');
								$('#password').parent().addClass('has-error');
								$('#yes-login').button('reset');
							}
						})
					});

					$('#login-modal').on('shown.bs.modal', function() {
						$('#username').focus();
					});

					$('#login-modal input').keypress(function(e) {
						e.preventDefault;
						if(e.which == 13) $('#yes-login').click();
					});

					$('#yes-logout').click(function(e) {
						e.preventDefault();
						$.ajax('functions.php?'+key+'action=logout').done(function() {
							$('[href=#playlists]').removeAttr('data-toggle').parent().addClass('disabled');
							$('[href=#files]').removeAttr('data-toggle').parent().addClass('disabled');
							$('[data-target=#logout-modal]').hide();
							$('[data-target=#login-modal]').show();
							$('#logout-modal').modal('hide');
						});
					});
				});
			</script>
			".Bootstrap::modal("logout-modal","<h1>".Bootstrap::glyphicon("remove-circle")." Log out?</h1>",NULL,"
				<a href=\"#\" class=\"btn btn-primary btn-lg\" id=\"yes-logout\">Yes</a>
				<a href=\"#\" class=\"btn btn-default btn-lg\" id=\"no-logout\" data-dismiss=\"modal\">No</a>")."
			".Bootstrap::modal("login-modal","<h1>".Bootstrap::glyphicon("play-circle")." Log In</h1>",NULL,"
				<div class=\"form-group\">
					<div class=\"input-group\">
						<span class=\"input-group-addon\">".Bootstrap::glyphicon("user")."</span>
						<input type=\"text\" class=\"form-control input-lg\" placeholder=\"Username\" id=\"username\">
					</div>
				</div>
				<div class=\"form-group\">
					<div class=\"input-group\">
						<span class=\"input-group-addon\">".Bootstrap::glyphicon("lock")."</span>
						<input type=\"password\" class=\"form-control input-lg\" placeholder=\"Password\" id=\"password\">
					</div>
				</div>
				<a href=\"#\" class=\"btn btn-primary btn-lg\" id=\"yes-login\" data-loading-text=\"Log in\">Log in</a>
				<a href=\"#\" class=\"btn btn-default btn-lg\" id=\"no-login\" data-dismiss=\"modal\">Cancel</a>")."
		</div>
	");

?>