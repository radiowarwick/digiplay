<?php
require_once('pre.php');
Output::set_title("Playout Reset");

if (Session::is_group_user("Studio Admin")){
echo("<div class=\"row\">
	<div class=\"col-md-3 list-group\">
		<a href=\"./studio/manage?location=1\" class=\"list-group-item\"><span class=\"glyphicon glyphicon-check\"></span> Manage Studio 1</a>
		<a href=\"./studio/manage?location=2\" class=\"list-group-item\"><span class=\"glyphicon glyphicon-check\"></span> Manage Studio 2</a>
	</div>
	<div class=\"col-md-4 jumbotron\">
		<h1>Reset Playout 1</h1>
		<button id=\"playout1\" type=\"button\" class=\"btn btn-danger col-md-12\">Reset</button>
	</div>
	<div class=\"col-md-1\"></div>
	<div class=\"col-md-4 jumbotron\">
		<h1>Reset Playout 2</h1>
		<button id=\"playout2\" type=\"button\" class=\"btn btn-danger col-md-12\">Reset</button>
	</div>
</div>");


echo("<script type=\"text/javascript\">
$('#playout1').click(function(){
	$.ajax({
		url: './ajax/restart_playout1.php',
		error: function(xhr,text,error) {
			value = $.parseJSON(xhr.responseText);
			alert(value.error);
		},
		success: function(data,text,xhr) {
			window.location.reload(true); 
		}
	});
	return(false);
});
$('#playout2').click(function(){
	$.ajax({
		url: './ajax/restart_playout2.php',
		error: function(xhr,text,error) {
			value = $.parseJSON(xhr.responseText);
			alert(value.error);
		},
		success: function(data,text,xhr) {
			window.location.reload(true); 
		}
	});
	return(false);
});
</script>");
}
?>