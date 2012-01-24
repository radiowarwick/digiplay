<?php
class AlertMessage {
	public function basic($class="info",$text="",$title="",$close=true) {
		$return = "<div class=\"alert-message ".$class.($close? " fade in" : "")."\"><p>";
		if($close) $return .= "<a class=\"close\" href=\"#\">&times;</a>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</p></div>";
		return $return;
	}	

	public function block($class="info",$text="",$title="",$close=false) {
		$return = "<div class=\"alert-message block-message ".$class.($close? " fade in" : "")."\"><p>";
		if($close) $return .= "<a class=\"close\" href=\"#\">&times;</a>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</p></div>";
		return $return;
	}
}
?>