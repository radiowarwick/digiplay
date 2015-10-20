<?php

Output::set_title("Sustainer");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_script(LINK_ABS."js/sustainer_schedule.js");
Output::add_stylesheet(LINK_ABS."css/select2.min.css");
Output::add_script(LINK_ABS."js/select2.min.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Perform common sustainer tasks");

echo("<style type=\"text/css\">
	td.timeslot { text-align: center; }
  </style>


	<script type=\"text/javascript\">
$(function() {

		$('#save-schedule').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/update-sustainer-slots.php',
				data: $('.field-slots').serialize(),
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

});
</script>");

$colours = array('2ecc71', 'e67e22', '3498db', 'e74c3c', '9b59b6', '34495e', '1abc9c', 'f1c40f');
$timeslots = array('00', '01', '02','03','04','05','06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');

$slots = SustainerSlots::get_all();
$i = 0;

echo("<h3>Sustainer schedule:</h3>");

echo("<select id=\"genre-selector\">");
foreach (Playlists::get_sustainer() as $playlist) {
	$i++;
	echo("<option value=\"".$playlist->get_id()."\" data-colour=\"".($playlist->get_colour() == "" ? 'FFFFFF' : $playlist->get_colour())."\">".$playlist->get_name()."</option>");
}
echo("</select>");

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
	echo($slot->get_audio_id() == NULL ? '' : "<span class=\"glyphicon glyphicon-time\" aria-hidden=\"true\"></span>");
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

echo("<button type=\"submit\" id=\"save-schedule\" class=\"btn btn-primary btn-block\">
		".Bootstrap::glyphicon("save save")."
		Save
	</button>");

echo("</form>");

// one modal
// js any box with class box can trigger modal
// get id on box (time,day) and pop that in modal
// modal has text input for id
// submit by javascript
// bordered or some shit to indicate prerec

echo(Bootstrap::modal("update-modal", "
		<form class=\"form-horizontal\" action=\"".LINK_ABS."ajax/add-update-prerecord.php\" method=\"POST\">
			<fieldset>
				<div class=\"control-group\">
					<label class=\"control-label\" for=\"audioid\">Audio ID</label>
					<div class=\"controls\">
						<input type=\"hidden\"class=\"update-id\" name=\"updateid\">
						<select id=\"prerecord-id\" name=\"prerecord-id\" data-width=\"100%\">
						</select>
						<p class=\"help-block\">Enter a prerecord's audio id to schedule.</p>
					</div>
				</div>
			</fieldset>
		</form>
	", "Schedule Prerecorded Content", "<a class=\"btn btn-primary update-playlist\" id=\"save-prerecord\" href=\"#\">Schedule</a><a class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</a>").

"<script type=\"text/javascript\">
		boxes = $('.timeslot');
		boxes.dblclick(function(){
			$('#update-modal').modal('show');
			$('.update-id').val($(this).attr('id'));
		});
</script>");

?>