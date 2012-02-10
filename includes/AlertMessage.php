<?php
class AlertMessage {
	public function basic($class="info",$text="",$title="",$close=true) {
		$return = "<div class=\"alert ".$class.($close? " fade in" : "")."\">";
		if($close) $return .= "<a class=\"close\" href=\"#\">&times;</a>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</div>";
		return $return;
	}	

	public function block($class="info",$text="",$title="",$close=false) {
		$return = "<div class=\"alert-block ".$class.($close? " fade in" : "")."\">";
		if($close) $return .= "<a class=\"close\" href=\"#\">&times;</a>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</div>";
		return $return;
	}
}
?>