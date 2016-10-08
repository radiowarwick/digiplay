<?php
Output::set_title("System Information");
MainTemplate::set_subtitle("View updates and report faults");
echo(
	"<h3>Report a Fault</h3>
	<p>Use the form below to report a fault. Please check the <b>system status</b> before reporting to check if the fault is already being dealt with.</p>
	<form role=\"form\" method=\"post\" action=\"../../ajax/add-update-fault.php\">
	  <div class=\"form-group\">
	    <label for=\"author\">Your name:</label>
	    <input type=\"text\" class=\"form-control\" id=\"author\" name=\"author\" placeholder=\"Enter email\" value=\"".Session::get_name()."\" readonly>
	  </div>
	  <div class=\"form-group\">
	    <textarea class=\"form-control\" rows=\"3\" name=\"content\"></textarea>
	  </div>
	  <button type=\"submit\" class=\"btn btn-default\">Submit</button>
	</form>
"
	); 
?>