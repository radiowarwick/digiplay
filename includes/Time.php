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
}
?>