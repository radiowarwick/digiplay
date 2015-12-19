<?php
Output::set_title("Group Administration");
MainTemplate::set_subtitle("View and edit groups");

Output::require_group("Group Admin");

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
			$('#group-info').attr('data-group-id',$(this).attr('data-group-id'));
			$('#group-members ul').html('');
			$.ajax({
				url: '".LINK_ABS."ajax/group-admin.php?action=members&group='+$(this).attr('data-group-id'),
				dataType: 'json'
			}).done(function(data) {
				$.each(data, function(id,user) {
					$('#group-members ul').append('<li class=\"list-group-item\" data-user-id=\"'+id+'\">'+user+'".Bootstrap::glyphicon("remove pull-right")."</li>');
				})
			})
		});

		$(document).on('click', '.glyphicon-remove', function() {
			$.ajax({
				url: '".LINK_ABS."ajax/group-admin.php?action=del-user&user='+$(this).parent().attr('data-user-id')+'&group='+$(this).parents('#group-info').attr('data-group-id'),
				dataType: 'json'
			}).done(function(data) {
				$.each(data, function(id,user) {
					$('[data-user-id='+id+']').remove();
				})
			})
		});

		$(document).on('submit', '.form-horizontal', function() {
			$.ajax({
				url: '".LINK_ABS."ajax/group-admin.php?action=add-user&user='+$(this).find('input').val()+'&group='+$(this).parents('#group-info').attr('data-group-id'),
				dataType: 'json'
			}).done(function(data) {
				$('input').val('');
				$.each(data, function(id,user) {
					$('#group-members ul').append('<li class=\"list-group-item\" data-user-id=\"'+id+'\">'+user+'".Bootstrap::glyphicon("remove pull-right")."</li>');
				})
			});
			return false;
		});

		$('#groups').find('li:first').click();
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
					<button type=\"submit\" class=\"btn btn-primary col-xs-3\" id=\"add-user-submit\">Add</button>
				</div>
			</div>
		</div>
	</div>
</div>
");

?>