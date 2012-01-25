<?php
class Time {
	public function seconds_to_dhms($input) {
		$unit_day = 86400; $unit_hour = 3600; $unit_minute = 60;

		$days = intval($input / $unit_day);
		$remaining = intval($input - ($days * $unit_day));

		$hours = intval($remaining / $unit_hour);
		$remaining = intval($remaining - ($hours * $unit_hour));

		$minutes = intval($remaining / $unit_minute);
		$remaining = intval($remaining - ($minutes * $unit_minute));

		$return = array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $remaining);
		return array_filter($return);
	}

	public function format_pretty($time) {
		$time_arr = Time::seconds_to_dhms($time);
		$time_str = ($time_arr["days"])? $time_arr["days"]." days, " : "";
		$time_str .= ($time_arr["hours"])? $time_arr["hours"]." hours, " : "";
		$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]." minutes, " : "";
		$time_str .= ($time_arr["seconds"])? $time_arr["seconds"]." seconds" : "";
		return $time_str;
	}

	public function format_succinct($time) {
		$time_arr = Time::seconds_to_dhms($time);
		$time_str = ($time_arr["days"])? $time_arr["days"]."d " : "";
		$time_str .= ($time_arr["hours"])? $time_arr["hours"]."h " : "";
		$time_str .= ($time_arr["minutes"])? $time_arr["minutes"]."m " : "0m ";
		$time_str .= ($time_arr["seconds"])? sprintf('%02d',$time_arr["seconds"])."s " : "00s ";
		return $time_str;
	}
}
?>