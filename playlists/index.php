<?php

Output::set_title("Playlists");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");

MainTemplate::set_subtitle("View and edit music playlists");

echo("<script type=\"text/javascript\">
$(function() {
	$('.table-striped tbody').sortable({ 
		axis: 'y',
		handle: '.move',
		helper: function(e, tr){
			var originals = tr.children();
			var helper = tr.clone();
			helper.children().each(function(index) {
				$(this).width(originals.eq(index).width())
			});
			return helper;
		},
		update : function () { 
			$('.move').removeClass('.glyphicon-move').addClass('.glyphicon-refresh');
            $.ajax({
                type: 'POST',
                url: '".LINK_ABS."/ajax/update-playlist-sortorder.php',
                data: $('.sortorder').serialize(),
                success: function(data) {
                	if(data != 'success') {
                		$('.sortorder').before('".Bootstrap::alert_message_basic("error","'+data+'","Error!")."');
                		$('.alert-message').alert();
                	}
                	$('.move').removeClass('.glyphicon-refresh').addClass('.glyphicon-move');
                }
            });
        }
	}).disableSelection();

	$('.info').popover({
		'html': true, 
		'trigger': 'hover',
		'title': function() { 
			return($(this).parent().parent().find('.title').html()+' tracks')
		},
		'content': function() {
			return($(this).parent().find('.hover-info').html());
		}
	});
	if(window.location.hash == '#add') {
		$('#add').click();
	}
	$('a[href=\"".LINK_ABS."playlists/index.php#add\"]').click(function() {
		event.preventDefault();
		$('#add').click();
	});
".(Session::is_group_user("Playlist Admin") ? "
		var playlist_id;
		$('.delete-playlist').click(function() {
			$('.delete-playlist-title').html($(this).parent().parent().find('.title').html());
			playlist_id = $(this).attr('data-dps-id');
		});

		$('.edit-playlist').click(function() {
			$('.playlist-edit-name').val($(this).parent().parent().find('.title').html());
			$('.update-id').val($(this).attr('data-dps-id'));
		});

		$('.yes-definitely-delete').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/delete-playlist.php',
				data: 'id='+playlist_id,
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

		$('.add-playlist').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/add-update-playlist.php',
				data: 'name='+$('.playlist-name').val(),
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

		$('.playlist-name').keypress(function(e) { if(e.keyCode == 13) { e.preventDefault(); $('.add-playlist').click(); }});



		$('.update-playlist').click(function() {
			$.ajax({
				url: '".LINK_ABS."ajax/add-update-playlist.php',
				data: 'id='+$('.update-id').val()+'&name='+$('.playlist-edit-name').val(),
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

		$('.playlist-edit-name').keypress(function(e) { if(e.keyCode == 13) { e.preventDefault(); $('.update-playlist').click(); }});
" : "").
"});
</script>");

echo("<h3>Current playlists:</h3>");

echo("
<form class=\"sortorder\">
<div class=\"table-responsive\">
<table class=\"table table-striped\">
	<thead>
		<tr>
			<th class=\"icon\"></th>
			<th>Title</th>
			<th class=\"icon\">Items</th>
			");
if(Session::is_group_user("Playlist Admin")) {
	echo("
			<th class=\"icon\"></th>
			<th class=\"icon\"></th>
			<th class=\"icon\"></th>
	");
}

echo("
		</tr>
	</thead>
	<tbody>
");

foreach (Playlists::get_all(false) as $playlist) {
	echo("
		<tr>
			<td>
				<a href=\"".LINK_ABS."playlists/detail/".$playlist->get_id()."\" class=\"info\">
					".Bootstrap::fontawesome("info-circle")."
					<input type=\"hidden\" name=\"id[]\" value=\"".$playlist->get_id()."\">
				</a>
				<div class=\"hover-info\">
				");
	$count = $playlist->count_tracks();
	foreach($playlist->get_tracks(10) as $track) {
		echo("<strong>".$track->get_title()."</strong> by ".$track->get_artists_str()."<br />");
	}
	if($count > 10) echo("<br />and <strong>".($count - 10)." more...<br />");
	echo("
				</div>
			</td>
			<td class=\"title\">".$playlist->get_name()."</td>
			<td>".count($playlist->get_tracks())."</td>
	");
	if(Session::is_group_user("Playlist Admin")) {
		echo("
			<td>
				<a href=\"#\" data-toggle=\"modal\" data-target=\"#update-modal\" data-dps-id=\"".$playlist->get_id()."\" class=\"edit-playlist\" title=\"Edit playlist name\" rel=\"twipsy\">
					".Bootstrap::fontawesome("pencil-alt")."
				</a>
			</td>
			<td>
				<a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-modal\" data-dps-id=\"".$playlist->get_id()."\" class=\"delete-playlist\" title=\"Delete this playlist\" rel=\"twipsy\">
					".Bootstrap::fontawesome("times-circle")."
				</a>
			</td>
			<td>
				<a href=\"#\" class=\"move\">
					".Bootstrap::fontawesome("arrows-alt-v", "move")."
				</a>
			</td>
		");
	}
	echo("
		</tr>");
}
echo("
	</tbody>
</table>
</div>
</form>
");

if(Session::is_group_user("Playlist Admin")) {

	echo("<h3>Sustainer playlists:</h3>");

	echo("
	<div class=\"table-responsive\">
	<table class=\"table table-striped\">
		<thead>
			<tr>
				<th class=\"icon\"></th>
				<th>Title</th>
				<th class=\"icon\">Items</th>
				<th class=\"icon\"></th>
				<th class=\"icon\"></th>
				<th class=\"icon\"></th>
			</tr>
		</thead>
		<tbody>
	");

	foreach (Playlists::get_sustainer() as $playlist) {
		echo("
			<tr>
				<td>
					<a href=\"".LINK_ABS."playlists/detail/".$playlist->get_id()."\" class=\"info\">
						".Bootstrap::fontawesome("info-circle")."
						<input type=\"hidden\" name=\"id[]\" value=\"".$playlist->get_id()."\">
					</a>
					<div class=\"hover-info\">
					");
		$count = $playlist->count_tracks();
		foreach($playlist->get_tracks(10) as $track) {
			echo("<strong>".$track->get_title()."</strong> by ".$track->get_artists_str()."<br />");
		}
		if($count > 10) echo("<br />and <strong>".($count - 10)." more...<br />");
		echo("
					</div>
				</td>
				<td class=\"title\">".$playlist->get_name()."</td>
				<td>".count($playlist->get_tracks())."</td>
				<td>
					<a href=\"#\" data-toggle=\"modal\" data-target=\"#update-modal\" data-dps-id=\"".$playlist->get_id()."\" class=\"edit-playlist\" title=\"Edit playlist name\" rel=\"twipsy\">
						".Bootstrap::fontawesome("pencil-alt")."
					</a>
				</td>
				<td>
					<a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-modal\" data-dps-id=\"".$playlist->get_id()."\" class=\"delete-playlist\" title=\"Delete this playlist\" rel=\"twipsy\">
						".Bootstrap::fontawesome("times-circle")."
					</a>
				</td>
				<td>
					<a href=\"#\" class=\"move\">
						".Bootstrap::fontawesome("arrows-alt-v", "move")."
					</a>
				</td>
			</tr>");
	}
	echo("
		</tbody>
	</table>
	</div>
	");

}

if(Session::is_group_user("Playlist Admin")) {
	echo("<a href=\"#\" data-toggle=\"modal\" data-target=\"#addnew-modal\" id=\"add\">Add a new playlist &raquo;</a>".
	Bootstrap::modal("addnew-modal", "
		<form class=\"form-horizontal\" action=\"".LINK_ABS."/ajax/add-update-playlist.php\" method=\"POST\">
			<fieldset>
				<div class=\"control-group\">
					<label class=\"control-label\" for=\"name\">Name</label>
					<div class=\"controls\">
						<input type=\"text\" class=\"form-control playlist-name\" id=\"name\">
						<p class=\"help-block\">Enter a name for the new playlist.</p>
					</div>
				</div>
			</fieldset>
		</form>
	", "Add new playlist", "<a class=\"btn btn-primary add-playlist\" href=\"#\">Save</a><a class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</a>").
	"</div>
</div>".
Bootstrap::modal("update-modal", "
		<form class=\"form-horizontal\" action=\"".LINK_ABS."/ajax/add-update-playlist.php\" method=\"POST\">
			<fieldset>
				<div class=\"control-group\">
					<label class=\"control-label\" for=\"name\">Name</label>
					<div class=\"controls\">
						<input type=\"hidden\"class=\"update-id\">
						<input type=\"text\" class=\"form-control playlist-edit-name\">
						<p class=\"help-block\">Enter a name for the playlist.</p>
					</div>
				</div>
			</fieldset>
		</form>
	", "Edit playlist name", "<a class=\"btn btn-primary update-playlist\" href=\"#\">Save</a><a class=\"btn btn-default\" data-dismiss=\"modal\">Cancel</a>").
	"</div>
</div>".
	Bootstrap::modal("delete-modal", "<p>Are you sure you want to permanently delete <span class=\"delete-playlist-title\">this playlist</span>? </p><p>(this does not delete any of the tracks on it)</p>", "Delete playlist", "<a href=\"#\" class=\"btn btn-primary yes-definitely-delete\">Yes</a> <a href=\"#\" class=\"btn btn-default\" data-dismiss=\"modal\">No</a>"));
}
?>