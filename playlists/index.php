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
		containment: 'parent',
		helper: fixHelper
	}).disableSelection();
});
</script>");

echo("<h2>Current playlists:</h2>");

echo("
<table class=\"zebra-striped\">
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
");
?>