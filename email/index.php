<?php
require_once('pre.php');
Output::set_title("Emails to the Studio");

$limit = (isset($_GET['n']))? $_GET['n'] : 10;
$page = ($_REQUEST['p']? $_REQUEST['p'] : 1);

$emails = Emails::get(NULL, NULL, NULL, $limit,(($page-1)*$limit));

	$pages = new Paginator;
	$pages->items_per_page = $limit;
	$pages->querystring = $query;
	$pages->mid_range = 3;
	$pages->items_total = 200;
	$pages->paginate();

	$low = (($page-1)*$limit+1);
	$high = (($low + $limit - 1) > 200)? 200 : $low + $limit - 1;
	
?>
<style type="text/css">
.subject, .sender { display:block; word-wrap: break-word; width:350px; overflow:hidden; text-overflow: ellipsis; white-space: nowrap; }
.subject { font-weight:bold; }
.sender { font-size:0.8em; }
small { display:block; margin-bottom:10px; }
#message { overflow:auto; border: 1px solid #DDD;
-webkit-border-radius: 6px;
-moz-border-radius: 6px;
border-radius: 6px;
-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.075);
-moz-box-shadow: 0 1px 2px rgba(0,0,0,.075);
box-shadow: 0 1px 2px rgba(0,0,0,.075);
border-image: initial; padding:10px; /*width:438px;*/ }
tbody tr { cursor:pointer; }
.pagination { text-align: center; }
</style>
<script type="text/javascript">
$(function(){
	$('tbody tr').click(function(){
		$('#message').load('../ajax/emailmessage.php?id='+$(this).attr('data-dps-email-id'));
	});
	$('#message').height($('#messagelist table').height()-20);
});
</script>
<div class="row">
<div class="col-span-6" id="messagelist">
<table class="table table-striped col-span-6">
<thead><tr><th class="col-span-2">Date</th><th class="col-span-8">Sender</th></tr></thead>
<tbody>
<?php

foreach ($emails as $email) {
echo("<tr data-dps-email-id=\"".$email->get_id()."\">
		<td>".date("d/m/y H:i", $email->get_datetime())."</td>
		<td><span class=\"subject\">".$email->get_subject()."</span><span class=\"sender\">".str_replace("\n", "",str_replace("<", " &lt;",str_replace(">", "&gt;",$email->get_sender())))."</span></td>
</tr>
");


}
?>
</tbody>
</table>
<div class="pagination_wrap">
<?php
echo($pages->return);
?>
</div>
</div>
<div class="col-span-6" id="message">
</div></div>