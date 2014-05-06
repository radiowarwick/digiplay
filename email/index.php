<?php

Output::set_title("Emails to the Studio");

$limit = (isset($_GET['n']))? $_GET['n'] : 10;
$page = (isset($_REQUEST['p'])? $_REQUEST['p'] : 1);

$emails = Emails::get(NULL, NULL, NULL, $limit,(($page-1)*$limit));

	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->mid_range = 3;
	$pages->items_total = Emails::count();
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > 200)? 200 : $low + $limit - 1;
	
?>
<style type="text/css">
.subject, .sender { display:block; word-wrap: break-word; width: 100%; overflow:hidden; text-overflow: ellipsis; white-space: nowrap; }
.subject { font-weight:bold; }
.sender { font-size:0.8em; }
small { display:block; margin-bottom:10px; }
#message { overflow:auto; }
tbody tr { cursor:pointer; }
.pagination { text-align: center; }
</style>
<script type="text/javascript">
$(function(){
	$('tbody tr').click(function(){
		$.getJSON('../ajax/emailmessage.php?id='+$(this).attr('data-dps-email-id'), function(data) {
			$('#message').html('<div class="panel-heading"><h4>'+data.subject+'</h4><h5>'+data.sender+'</h5></div><div class="panel-body">'+data.message+'</div>');
		});
	});
	$('#message').height($('#messagelist table').height()-20);
});
</script>
<div class="row">
	<div class="col-md-6" id="messagelist">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-xs-2">Date</th>
					<th class="col-xs-10">Sender</th>
				</tr>
			</thead>
			<tbody>
<?php

foreach ($emails as $email) {
echo("<tr data-dps-email-id=\"".$email->get_id()."\">
		<td class=\"col-xs-2\">".date("d/m/y H:i", $email->get_datetime())."</td>
		<td class=\"col-xs-10\"><div class=\"subject\">".$email->get_subject()."</span><span class=\"sender\">".str_replace("\n", "",str_replace("<", " &lt;",str_replace(">", "&gt;",$email->get_sender())))."</span></td>
</tr>
");


}
?>
			</tbody>
		</table>
		<div class="pagination_wrap">
		<?php echo($pages->return); ?>
	</div>
</div>
<div class="col-md-6">
	<div class="panel panel-default" id="message">
	</div>
</div>
</div>