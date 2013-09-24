<?php
Output::set_title("Studio Management");
Output::add_script(LINK_ABS."js/moment.min.js");
Output::add_stylesheet(LINK_ABS."css/studio.css");
MainTemplate::set_body_class('manage');

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
			var key = 'key=".$key."&';
			var timers = [];
			var connect_timeout;
			var websocket;
			var connection = false;

			function startWebsocket() {
				console.log('Starting websocket...');
				websocket = new WebSocket('".Configs::get_system_param("websocket_uri")."');
				websocket.onopen = function(e) { onOpen(e) };
				websocket.onclose = function(e) { onClose(e) };
				websocket.onmessage = function(e) { onMessage(e) };
				websocket.onerror = function(e) { onError(e) };
			}

			function onOpen(e) {
				connection = true;
				console.log('Websocket connection established.');
				websocket.send(JSON.stringify({'ident':'".$key."'}));
				clearTimeout(connect_timeout);
				$.each(timers, function(i, v) { clearInterval(v); });
				timers = [];
			}

			function onClose(e) {
				connection = false;
				console.log('Websocket connection lost.');
				connect_timeout = setTimeout('setIntervals()', 5000);
				setTimeout('startWebsocket()',5000);
			}

			function onMessage(e) {
				data = JSON.parse(e.data);
				console.log(data);
				switch(data.channel) {
					case 't_log':
						reloadLog();
						break;
					case 't_email':
						reloadMessages();
						break;
					case 't_playlists':
						reloadPlaylists();
						break;
					case 't_configuration':
						switch(data.payload.parameter) {
							case 'current_showitems_id':
								$('#showplan .panel').removeClass('panel-primary').addClass('panel-default');
								$('[data-item-id='+data.payload.val+']').removeClass('panel-default panel-info').addClass('panel-primary');
								break;
							case 'next_on_showplan':
								if(data.payload.val == '') {
									if($('#showplan .panel-primary').is(':last-child')) $(this).removeClass('panel-primary').addClass('panel-default');
									$('#showplan .panel-primary').removeClass('panel-primary').addClass('panel-default').next('.showplan-audio').dblclick();
								}
								break;
						}
						break;
					case 't_audiowall':
						break;
					case 't_showitems':
						reloadShowplan();
						break;
				}
			}

			function onError(e) {
				console.log(e);
			}

			function setIntervals() {
				if(timers.length == 0) {
					console.log('Setting manual refresh intervals.');
					timers.push(setInterval('reloadShowplan()', 5000));
					timers.push(setInterval('reloadInfo()', 60000));
					timers.push(setInterval('reloadMessages()', 30000));
					timers.push(setInterval('reloadPlaylists()', 60000));
					timers.push(setInterval('reloadLog()', 30000));
				}
			}

			$(function() {
				connect_timeout = setTimeout('setIntervals()', 5000);
				startWebsocket();
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
									function reloadInfo() {
										$('#info-content').load('functions.php?'+key+'action=info-content');
									};
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
									function reloadMessages() {
										var active_message;
										$.ajax('functions.php?'+key+'action=messages').done(function(data) {
											$('#message-list tr').each(function() { 
												if($(this).hasClass('selected')) active_message = $(this).attr('data-message-id');
												$('#message-list').html(data);
												$('[data-message-id='+active_message+']').addClass('selected');
											});
										});
									}

									$(function() { 
										$('#message-list').load('functions.php?'+key+'action=messages');

										$(document).on('click', '#message-list tbody tr', function() { 
											$('#message-content h4').html($(this).find('.subject').html());
											$('#message-content iframe').attr('src', 'functions.php?'+key+'action=message&id='+$(this).attr('data-message-id')); 
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
									function reloadPlaylists() {
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
									}

									$(function() { 
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
									function reloadLog() {
										$('#log').load('functions.php?'+key+'action=log');
									}

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

										$('#log').load('functions.php?'+key+'action=log');
									});
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
									<h1 class=\"loading\">".Bootstrap::glyphicon("refresh rotate")."</h1>
								</div>
							</div>
						</div>
					</div>
					<div class=\"col-md-5 hidden-sm hidden-xs\" id=\"right-panel\">
						<script>
							function reloadShowplan() {
								var expanded_items = [];
								var previous_items = [];
								var selected_item;
								var current_audio;
								$.ajax('functions.php?'+key+'action=showplan').done(function(data) {
									$('#showplan .panel').each(function() { 
										previous_items.push($(this).attr('data-item-id'));
										if($(this).find('.panel-collapse').hasClass('in')) {
											expanded_items.push($(this).find('.panel-collapse').attr('id'));
										}
										if($(this).hasClass('panel-info')) {
											selected_item = $(this).attr('data-item-id');
										}
										if($(this).hasClass('panel-primary')) {
											current_audio = $(this).attr('data-item-id');
										}
									});
									$('#showplan').html(data);
									$.each(expanded_items, function(key, value) {
										$('#'+value).addClass('in');
									});
									if(!$('#showplan .panel-primary').length) {
										$.each($('#showplan .panel'), function(key, value) {
											if($.inArray($(this).attr('data-item-id'), previous_items) == -1) {
												$(this).dblclick();
											}
										});
									}
									if(!$('[data-item-id='+current_audio+']').hasClass('panel-primary')) {
										$('[data-item-id='+current_audio+']').next('.showplan-audio').dblclick();
									}
									if(typeof selected_item != 'undefined') {
										$('[data-item-id='+selected_item+']').addClass('panel-info');
									}
								});
							}

							$(function() { 
								$('#showplan').load('functions.php?'+key+'action=showplan');

								$(document).on('dblclick', '#showplan .showplan-audio', function() {
									$(this).find('.controls').hide();
									$(this).find('.duration').show();
									$.ajax({
										url: 'functions.php?'+key+'action=set-current&id='+$(this).attr('data-item-id'),
										dataType: 'json'
									}).done(function(data) {
										if(data.response == 'success') {
											if(!connection) {
												$('#showplan .panel').removeClass('panel-primary').addClass('panel-default');
												$('[data-item-id='+data.id+']').removeClass('panel-default panel-info').addClass('panel-primary');
											}
										}
									});
								});

								$(document).on('click', 'tbody tr', function() { 
									$(this).parentsUntil('.tab-pane').find('tr').removeClass('selected');
									$(this).addClass('selected');
								});

								$(document).on('click', '#showplan .panel', function() { 
									$(this).parent().find('.panel:not(.panel-primary)').removeClass('panel-info').addClass('panel-default');
									if(!$(this).hasClass('panel-primary')) {
										$(this).addClass('panel-info');
									}
								});

								$(document).on('dblclick', '.track', function() {
									$.ajax({
										url: 'functions.php?'+key+'action=showplan-append&id='+$(this).attr('data-track-id'),
										dataType: 'json'
									}).done(function(data) {
										if(!connection) reloadShowplan();
									});
								});

								$(document).on('mouseenter', '#showplan .panel:not(.panel-primary)', function() {
									$(this).find('.duration').hide();
									$(this).find('.controls').show();
								});

								$(document).on('mouseleave', '#showplan .panel:not(.panel-primary)', function() {
									$(this).find('.controls').hide();
									$(this).find('.duration').show();
								});

								$(document).on('click', '.controls .glyphicon-remove', function() {
									$.ajax({
										url: 'functions.php?'+key+'action=showplan-remove&id='+$(this).parents('.panel').attr('data-item-id'),
										dataType: 'json'
									}).done(function(data) {
										reloadShowplan();
									});
								});
							})
						</script>

						<h2 id=\"showplan-title\"><span>Showplan</span><div class=\"pull-right clear-showplan\" data-toggle=\"modal\" data-target=\"#clear-showplan-modal\">".Bootstrap::glyphicon("trash")."</div></h2>
						<div class=\"list-group\" id=\"showplan\">
							<h1 class=\"loading\">".Bootstrap::glyphicon("refresh rotate")."</h1>
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
							if(typeof key == 'undefined') {
								window.location('".LINK_ABS."?refer=studio/manage/');
							}
							$('[href=#info]').tab('show');
							$('[href=#playlists]').removeAttr('data-toggle').parent().addClass('disabled');
							$('[href=#files]').removeAttr('data-toggle').parent().addClass('disabled');
							$('[data-target=#logout-modal]').hide();
							$('[data-target=#login-modal]').show();
							$('#logout-modal').modal('hide');
						});
					});

					$(document).on('click', '#yes-clear-showplan', function() {
						$.ajax({
							url: 'functions.php?'+key+'action=showplan-clear',
							dataType: 'json'
						}).done(function(data) {
							reloadShowplan();
							$('#clear-showplan-modal').modal('hide');
						});
					});
				});
			</script>
			".Bootstrap::modal("clear-showplan-modal","<h1>".Bootstrap::glyphicon("trash")."Clear showplan?</h1>",NULL,"
				<a href=\"#\" class=\"btn btn-primary btn-lg\" id=\"yes-clear-showplan\">Yes</a>
				<a href=\"#\" class=\"btn btn-default btn-lg\" id=\"no-clear-showplan\" data-dismiss=\"modal\">No</a>")."
			".Bootstrap::modal("logout-modal","<h1>".Bootstrap::glyphicon("remove-circle")." Log out?</h1>",NULL,"
				<a href=\"#\" class=\"btn btn-primary btn-lg\" id=\"yes-logout\">Yes</a>
				<a href=\"#\" class=\"btn btn-default btn-lg\" id=\"no-logout\" data-dismiss=\"modal\">No</a>")."
			".Bootstrap::modal("login-modal","<h1>".Bootstrap::glyphicon("play-circle")." Log In</h1>",NULL,"
				<div class=\"form-group\">
					<div class=\"input-group\">
						<span class=\"input-group-addon\">".Bootstrap::glyphicon("user")."</span>
						<input type=\"text\" class=\"form-control input-lg\" placeholder=\"Username\" id=\"username\" autocomplete=\"off\">
					</div>
				</div>
				<div class=\"form-group\">
					<div class=\"input-group\">
						<span class=\"input-group-addon\">".Bootstrap::glyphicon("lock")."</span>
						<input type=\"password\" class=\"form-control input-lg\" placeholder=\"Password\" id=\"password\" autocomplete=\"off\">
					</div>
				</div>
				<a href=\"#\" class=\"btn btn-primary btn-lg\" id=\"yes-login\" data-loading-text=\"Log in\">Log in</a>
				<a href=\"#\" class=\"btn btn-default btn-lg\" id=\"no-login\" data-dismiss=\"modal\">Cancel</a>")."
		</div>
	");

?>