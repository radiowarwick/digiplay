<?php

Output::set_title("Sustainer Schedule");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_stylesheet(LINK_ABS."css/select2.min.css");
Output::add_script(LINK_ABS."js/select2.min.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Change the schedule of the sustainer service");

echo("<style type=\"text/css\">
	td.timeslot { text-align: center; }
  </style>


	<script type=\"text/javascript\">
$(function() {

		$('#update-playlist').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/update-sustainer-slots.php',
				data: { updateid: $('.update-id').val(), playlistid: $('#playlist-id').val() },
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
		});

		$('#save-prerecord').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/add-update-prerecord.php',
				data: { updateid: $('.update-id').val(), prerecordid: $('#prerecord-id').val() },
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
		});

		$('#delete-prerecord').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/add-update-prerecord.php',
				data: { updateid: $('.update-id').val(), prerecordid: 0 },
				type: 'POST',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					window.location.reload(true); 
				}
			});
		});

		$('#prerecord-id').select2({
		  ajax: {
		    url: '".LINK_ABS."ajax/prerecord-search.php',
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
		      return {
		        q: params.term
		      };
		    },
		    processResults: function (data, page) {
		      // parse the results into the format expected by Select2.
		      // since we are using custom formatting functions we do not need to
		      // alter the remote JSON data
		      return {
		        results: data.data
		      };
		    },
		    cache: true
		  },
		  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		  minimumInputLength: 1,
		  templateResult: formatRepo, // omitted for brevity, see the source of this page
		  templateSelection: formatRepoSelection
		});

		function formatRepo (repo) {
			if (repo.loading) return repo.title;
		    return repo.title + ' by <i>' + repo.by + '</i>';
		  }

	  function formatRepoSelection (repo) {
	  	return repo.title;
	  }

	  $('#playlist-id').select2()

});
</script>");

$timeslots = array('00', '01', '02','03','04','05','06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');

$slots = SustainerSlots::get_all();
$i = 0;

echo("<table class=\"table table-striped table-bordered\">
	<thead>
	<tr>
	<th></th>
	<th>Monday</th>
	<th>Tuesday</th>
	<th>Wednesday</th>
	<th>Thursday</th>
	<th>Friday</th>
	<th>Saturday</th>
	<th>Sunday</th>
	</tr>
	</thead>
	<tbody>");
$i = 0;
foreach ($slots as $slot) {
	if ($i < 1) {
		echo("<tr>
			<td>".$slot->get_time().":00</td>");
	}
	$thisPlaylist = Playlists::get_by_id($slot->get_playlist_id());
	$thisPlaylistColour = ($thisPlaylist->get_colour() == "" ? 'FFFFFF' : $thisPlaylist->get_colour());
	echo("<td class='timeslot' id='slot-".$slot->get_day()."-".$slot->get_time()."' style='background-color: #".$thisPlaylistColour.";'>");
	echo($slot->get_audio_id() == NULL ? '' : Bootstrap::fontawesome("clock"));
	echo("</td>");
	$i++;
	if ($i > 6) {
		echo("</tr>");
		$i = 0;
	}
}
echo("</tbody>
	</table>");

echo("<form>");

foreach ($slots as $slot) {
	echo("<input type=\"hidden\" class=\"field-slots\" id=\"field-slot-".$slot->get_day()."-".$slot->get_time()."\" name=\"field-slot-".$slot->get_day()."-".$slot->get_time()."\" value=\"".$slot->get_playlist_id()."\">");
}

echo("</form>");

$playlistOptions = "";

foreach (Playlists::get_sustainer() as $playlist) {
	$playlistOptions .= "<option value=\"".$playlist->get_id()."\">".$playlist->get_name()."</option>";
}

echo(Bootstrap::modal("update-modal", "
		<p id=\"slot-info\">Current slot information is unavailable.</p>
		<hr>
		<form class=\"form-horizontal\" action=\"?\" method=\"POST\">
			<fieldset>
				<div class=\"control-group\">
					<label class=\"control-label\" for=\"playlist-id\">New Playlist</label>
					<div class=\"controls\">
						<select id=\"playlist-id\" name=\"playlist-id\" data-width=\"100%\">
							".$playlistOptions."
						</select>
						<p class=\"help-block\">Select the playlist to be scheduled for <span class='schedule-time'></span>.</p>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<div class=\"control-group\">
					<label class=\"control-label\" for=\"prerecord-id\">Prerecorded File</label>
					<div class=\"controls\">
						<select id=\"prerecord-id\" name=\"prerecord-id\" data-width=\"100%\">
						</select>
						<p class=\"help-block\">Select the prerecorded content to be played out at <span class='schedule-time'></span>.</p>
					</div>
				</div>
			</fieldset>
			<input type=\"hidden\"class=\"update-id\" name=\"updateid\">
		</form>
	", "Schedule Prerecorded Content", "<a class=\"btn btn-success\" id=\"update-playlist\" href=\"#\">Update Playlist</a><a class=\"btn btn-primary\" id=\"save-prerecord\" href=\"#\">Schedule Prerecord</a><a class=\"btn btn-danger\" id=\"delete-prerecord\" href=\"#\">Unschedule Prerecord</a><a class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</a>").

"<script type=\"text/javascript\">
		boxes = $('.timeslot');
		boxes.dblclick(function(){
			$('#update-modal').modal('show');
			$('.update-id').val($(this).attr('id'));
			splitTimeslot = $(this).attr('id').split('-');
			days = ['', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
			day = (days[splitTimeslot[1]]);
			time = splitTimeslot[2] + ':00';
			$('.schedule-time').text(day + ' ' + time);
			$.ajax({
				url: '".LINK_ABS."ajax/get-slot-status.php',
				data: { updateid: $('.update-id').val() },
				type: 'POST',
				dataType: 'json',
				error: function(xhr,text,error) {
					value = $.parseJSON(xhr.responseText);
					alert(value.error);
				},
				success: function(data,text,xhr) {
					$('#slot-info').html(data.status);
				}
			});
		});
</script>");

?>