<?php

Output::set_title("Sustainer Control Centre");
Output::add_script(LINK_ABS."js/jquery-ui-1.10.3.custom.min.js");

Output::require_group("Sustainer Admin");

MainTemplate::set_subtitle("Perform common sustainer tasks");

if ((isset($_POST['trackid']) || isset($_GET['trackid'])) && Session::is_group_user("Administrators")) {
	$query = "SELECT * FROM audio WHERE id=:trackid";
	$parameters = array(':trackid' => $_REQUEST['trackid']);
	$result = DigiplayDB::query($query, $parameters);
	if( $result->rowCount() != 1 ) {
		echo(Bootstrap::alert_message_basic("danger","Couldn't find track ID in the digiplay audio DB."));
	} else {
		$track = $result->fetch();
		$query = "SELECT * FROM sustschedule order by id asc limit 1";
		$result = DigiplayDB::query($query);
		$scheduleslot = $result->fetch();
		if ($track['id'] != $scheduleslot['audioid']) {
			$query = "UPDATE sustschedule SET audioid=:trackid, trim_start_smpl=0, trim_end_smpl = :tracklength, fade_in = 0, fade_out = :tracklength WHERE id = :scheduleslot";
			$parameters = array(':trackid' => $track['id'], ':tracklength' => $track['length_smpl'], ':scheduleslot' => $scheduleslot['id']);
			DigiplayDB::query($query, $parameters);
			$query = "INSERT INTO sustlog (audioid,userid,timestamp) VALUES (:audioid,:userid,:timestamp)";
			date_default_timezone_set("Europe/London");
			$parameters = array(':audioid' => $track['id'], ':userid' => Session::get_id(), ':timestamp' => time());
			DigiplayDB::query($query, $parameters);
			echo(Bootstrap::alert_message_basic("info","Track Scheduled."));
		} else {
		 	echo(Bootstrap::alert_message_basic("warning","This track is already at the top of the queue."));
		}
	}
}

$currentQueue = Sustainer::get_queue();
$i = 0;
?>
<h3>Current Queue</h3>
<?php
if (!is_null($currentQueue)) {

	if (array_key_exists('id', $currentQueue)) {
		$currentQueueTemp = array(0 => $currentQueue);
		$currentQueue = $currentQueueTemp;
	}
?>
<table class="table table-striped table-bordered">
	<thead>
	<tr>
	<th></th>
	<th>Title</th>
	<th>Artist</th>
	<th>Album</th>
	</tr>
	</thead>
	<tbody>
    <?php
    	foreach ($currentQueue as $row) {
	    $i++;
	?>
      <tr>
	      <td><?php echo($i); ?></td>
        <td><?php echo($row['title']); ?></td>
        <td><?php echo($row['artist']); ?></td>
        <td><?php echo($row['album']); ?></td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<?php
}
else {
	Bootstrap::alert("warning","<b>Warning: </b>The current queue is empty","",false);
}

if(Session::is_group_user("Administrators")) {
?>

<h3>Schedule Audio</h3>
<p>You can use this tool to schedule the next audio track to be played on Sue by using its Audio ID.</p>
<form method="post">
	<div class="form-group">
		<label for="trackid">Track ID</label>
		<input type="text" class="form-control" name="trackid" id="trackid">
	</div>
	<button class="btn btn-primary" type="submit" name="submit">Schedule</button>
</form>

<?php
$currentLog = Sustainer::get_log();
$i = 0;
?>

<h3>Scheduler Log</h3>
<table class="table table-striped table-bordered">
	<thead>
  	<tr>
    	<th>Date</th>
    	<th>Title</th>
    	<th>Artist</th>
    	<th>Scheduled By</th>
  	</tr>
	</thead>
	<tbody>
    <?php 
    	foreach ($currentLog as $row) {
    	$i++;
    ?>
    	<tr>
    		<td><?php echo(date('d/m/y H:i', $row['timestamp'])); ?></td>
      	<td><?php echo($row['title']); ?></td>
      	<td><?php echo($row['artist']); ?></td>
      	<td><?php echo($row['username']); ?></td>
    	</tr>
    <?php } ?>
  </tbody>
</table>

<?php
}
?>
