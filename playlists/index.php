<?php
require_once('pre.php');
Output::set_title("Playlists");
Output::add_script(SITE_LINK_REL."js/jquery-ui-1.8.17.custom.min.js");

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
			<th class=\"icon\"></th>
			<th class=\"icon\"></th>
			<th class=\"icon\"></th>
		</tr>
	</thead>
	<tbody>
");

foreach (Playlists::get_all() as $playlist) {
	echo("
		<tr>
			<td>
				<a href=\"".SITE_LINK_REL."playlists/edit/".$playlist->get_id()."\">
					<img src=\"".SITE_LINK_REL."images/icons/information.png\" />
					<input type=\"hidden\" name=\"id[]\" value=\"".$playlist->get_id()."\">
				</a>
			</td>
			<td>".$playlist->get_name()."</td>
			<td>".count($playlist->get_tracks())."</td>
			<td>
				<a href=\"".SITE_LINK_REL."playlists/edit/".$playlist->get_id()."\">
					<img src=\"".SITE_LINK_REL."images/icons/cd_edit.png\" />
				</a>
			</td>
			<td>
				<a href=\"".SITE_LINK_REL."playlists/edit/?delete=".$playlist->get_id()."\">
					<img src=\"".SITE_LINK_REL."images/icons/delete.png\" />
				</a>
			</td>
			<td>
				<a href=\"#\" class=\"move\">
					<img src=\"".SITE_LINK_REL."images/icons/move.png\" />
				</a>
			</td>
		</tr>");
}
echo("
	</tbody>
</table>
</form>
");
?>