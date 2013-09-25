<?php
Output::set_title("Group Administration");
MainTemplate::set_subtitle("View and edit groups");

function print_groups($group) {
	$groups = Groups::get_by_parent($group);
	if($groups) {
		echo("<ul>");
		foreach($groups as $group) {
			echo("<li data-group-id=\"".$group->get_id()."\" data-description=\"".$group->get_description()."\">".$group->get_name()."</li>");
			print_groups($group);
		}
		echo("</ul>");
	}
}

echo("
	<script>
	$(document).ready(function() {
		$(document).on('click', '#groups li', function() {
			$('#group-info h3').html($(this).html());
			$('#group-description').html($(this).attr('data-description'));
			$.ajax({
				url: '".LINK_ABS."ajax/group-admin.php?action=members&group='+$(this).attr('data-group-id'),
				dataType: 'json'
			}).done(function(data) {
				$('#group-members ul').html('');
				$.each(data, function(id,user) {
					$('#group-members ul').append('<li class=\"list-group-item\">'+user+'</li>');
				})
			})
		})
	});
	</script>

	<div class=\"row\">
		<div class=\"col-md-6\">
			<h3>Group tree</h3>
			<div id=\"groups\">
");

print_groups(NULL);

echo("
		</div>
	</div>
	<div class=\"col-md-6\">
		<div id=\"group-info\">
			<h3></h3>
			<div id=\"group-description\"></div>
			<h4>Members:</h4>
			<div id=\"group-members\">
				<ul class=\"list-group\">

				</ul>
			</div>
			<form class=\"form-horizontal\">
				<div class=\"form-group\">
					<div class=\"col-xs-9\">
						<input type=\"text\" id=\"add-user\" class=\"form-control\" placeholder=\"Add user...\" />
					</div>
					<button class=\"btn btn-primary col-xs-3\" id=\"add-user-submit\">Add</button>
				</div>
			</div>
		</div>
	</div>
</div>
");

?>