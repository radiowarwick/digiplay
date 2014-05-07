<?php
Output::set_title("Administration");
MainTemplate::set_subtitle("Control every aspect of the Digiplay system");

$trash = Files::get_by_id(3, "dir");

echo("
<div class=\"row\">
	<div class=\"col-sm-4\">
		<a href=\"".LINK_ABS."music\">
			<div class=\"panel panel-info\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
							".Bootstrap::glyphicon("volume-up icon-huge")."
						</div>
						<div class=\"col-xs-7\">
							<h2>".Tracks::get_total_tracks()."</h2>
							<h4>tracks</h4>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class=\"col-sm-4\">
		<a href=\"".LINK_ABS."admin/users\">
			<div class=\"panel panel-success\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
							".Bootstrap::glyphicon("user icon-huge")."
						</div>
						<div class=\"col-xs-7\">
							<h2>".Users::count()."</h2>
							<h4>users</h4>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class=\"col-sm-4\">
		<a href=\"".LINK_ABS."showplans\">
			<div class=\"panel panel-default\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
							".Bootstrap::glyphicon("tasks icon-huge")."
						</div>
						<div class=\"col-xs-7\">
							<h2>".Showplans::count()."</h2>
							<h4>showplans</h4>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>
<div class=\"row\">
	<div class=\"col-sm-4\">
		<a href=\"".LINK_ABS."audiowalls\">
			<div class=\"panel panel-default\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
							".Bootstrap::glyphicon("th icon-huge\" style=\"margin-top: -4px;")."
						</div>
						<div class=\"col-xs-7\">
							<h2>".AudiowallSets::count()."</h2>
							<h4>audiowalls</h4>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class=\"col-sm-4\">
		<a href=\"".LINK_ABS."email\">
			<div class=\"panel panel-warning\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
							".Bootstrap::glyphicon("envelope icon-huge")."
						</div>
						<div class=\"col-xs-7\">
							<h2>".Emails::count_unread()."</h2>
							<h4>e-mails</h4>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class=\"col-sm-4\">
		<a href=\"".LINK_ABS."admin/trash\">
			<div class=\"panel panel-danger\">
				<div class=\"panel-heading\">
					<div class=\"row dashboard-stamp\">
						<div class=\"col-xs-5\">
							".Bootstrap::glyphicon("trash icon-huge")."
						</div>
						<div class=\"col-xs-7\">
							<h2>".$trash->count()."</h2>
							<h4>in trash</h4>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>");
?>