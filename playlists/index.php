<?php
require_once('pre.php');
Output::set_title("Playlists");
Output::add_script(SITE_LINK_REL."js/jquery-ui-1.8.17.custom.min.js");
Output::add_script(SITE_LINK_REL."js/bootstrap-popover.js");

MainTemplate::set_subtitle("View and edit music playlists");

echo("<script type=\"text/javascript\">
$(function() {
	var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
	};
	$('.zebra-striped tbody').sortable({ 
		axis: 'y',
		handle: '.move',
		helper: fixHelper,
		update : function () { 
            $.ajax({
                type: \"POST\",
                url: \"/ajax/update-playlist-sortorder.php\",
                data: $(\".sortorder\").serialize(),
                success: function(data) {
                	if(data == \"success\") {
                		$('.ajax-loader').remove();
                	} else {
                		$('.sortorder').before('".AlertMessage::basic("error","'+data+'","Error!")."');
                		$('.alert-message').alert();
                	}
                }
            });
        }
	}).disableSelection();

	$('.info').popover({
		'html': true, 
		'title': function() { 
			return($(this).parent().parent().find('.title').html()+' tracks')
		},
		'content': function() {
			return($(this).parent().find('.hover-info').html());
		}
	});
});
</script>");

echo("<h2>Current playlists:</h2>");

echo("
<form class=\"sortorder\">
<table class=\"table table-striped\">
	<thead>
		<tr>
			<th class=\"icon\"></th>
			<th class=\"title\">Title</th>
			<th class=\"icon\">Items</th>
			");
if(Session::is_group_user("playlist_admin")) {
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

foreach (Playlists::get_all() as $playlist) {
	echo("
		<tr>
			<td>
				<a href=\"#\" class=\"info\">
					<img src=\"".SITE_LINK_REL."images/icons/information.png\" />
					<input type=\"hidden\" name=\"id[]\" value=\"".$playlist->get_id()."\">
				</a>
				<div class=\"hover-info\">
				");
	foreach($playlist->get_tracks() as $track) {
		echo("<strong>".$track->get_title()."</strong> by ".$track->get_artists_str()."<br />");
	}
	echo("
				</div>
			</td>
			<td class=\"title\">".$playlist->get_name()."</td>
			<td>".count($playlist->get_tracks())."</td>
	");
	if(Session::is_group_user("playlist_admin")) {
		echo("
			<td>
				<a href=\"".SITE_LINK_REL."playlists/edit/".$playlist->get_id()."\" title=\"Edit this playlist\" rel=\"twipsy\">
					<img src=\"".SITE_LINK_REL."images/icons/cd_edit.png\" />
				</a>
			</td>
			<td>
				<a href=\"".SITE_LINK_REL."playlists/edit/?delete=".$playlist->get_id()."\" title=\"Delete this playlist\" rel=\"twipsy\">
					<img src=\"".SITE_LINK_REL."images/icons/delete.png\" />
				</a>
			</td>
			<td>
				<a href=\"#\" class=\"move\">
					<img src=\"".SITE_LINK_REL."images/icons/move.png\" />
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
</form>
");

if(Session::is_group_user("playlist_admin")) {
	echo("
<a href=\"#\" data-controls-modal=\"addnew-modal\" data-backdrop=\"true\" data-keyboard=\"true\">Add a new playlist &raquo;</a>
<div class=\"modal hide fade\" id=\"addnew-modal\">
	<div class=\"modal-header\">
		<a class=\"close\" href=\"#\">&times;</a>
		<h3>Add new playlist</h3>
	</div>
	<div class=\"modal-body\">
		You'll lose any unsaved changes on this page.
	</div>
	<div class=\"modal-footer\">
		<a class=\"btn primary\" href=\"#\">Save</a>
		<a class=\"btn\" href=\"#\">Cancel</a>
	</div>
</div>");
}
?>