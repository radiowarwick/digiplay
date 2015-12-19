<?php

Output::set_title("My Files");
MainTemplate::set_subtitle("Browse and manage files in the database");

Output::add_stylesheet(LINK_ABS."css/ui.fancytree.css");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");
Output::add_script(LINK_ABS."js/jquery.fancytree.js");
Output::add_script(LINK_ABS."js/jquery.fancytree.glyphicon.js");

/*echo("
	<script>
		$().ready(function() {
			$('#files').fancytree({ 
				source: { url: '".LINK_ABS."ajax/file-tree.php?id=1', cache: false },
				extensions: ['glyphicon'],
				lazyload: function(event, data) {
					var node = data.node;
					data.result = {	url: '".LINK_ABS."ajax/file-tree.php?id='+node.data.id }
				}
			});
		});
	</script>

	<div id=\"files\"></div>"
);*/

?>

<em>Coming soon!</em>
