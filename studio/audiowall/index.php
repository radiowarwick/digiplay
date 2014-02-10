<?php

Output::add_stylesheet(LINK_ABS."css/audiowall.css");
Output::set_title("Audiowall");
MainTemplate::set_body_class("audiowall");

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
				switch(data.channel) {
					case 't_audiowall':
						break;
				}
			}

			function onError(e) {
				console.log(e);
			}

			function setIntervals() {
				if(timers.length == 0) {
					console.log('Setting manual refresh intervals.');
					timers.push(setInterval('reloadAudiowall()', 30000));
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

				$(document).keydown(function(e) {
					if((111 < e.which) && (e.which < 123)) e.preventDefault();
				});
			});

		</script>
");

$set = AudiowallSets::get_by_id($location->get_config("station_aw_set")->get_val());

$wall = $set->get_walls()[0];
$items = $wall->get_items();

echo("<div class=\"row\">");
for($i = 0; $i < 12; $i++) {
	echo("<div class=\"col-xs-4\">");
	if(isset($items[$i])) {
		$style = $items[$i]->get_style();
		$audio = $items[$i]->get_audio();
		echo("<div class=\"btn btn-block\" style=\"color: ".$style->get_foreground_rgb()."; background: ".$style->get_background_rgb()."; border-color: ".$style->get_accent_rgb()."\">".$items[$i]->get_text()."<br />".$audio->get_length_formatted()."</div>");
	} else {
		echo("<div class=\"well\"></div>");
	}
	echo("</div>");
	if(((($i+1) % 3) == 0) && ($i < 9)) echo("</div><div class=\"row\">");
}
echo("</div>");

?>