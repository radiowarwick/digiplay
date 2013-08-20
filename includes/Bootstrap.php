<?php
class Bootstrap {
	public function glyphicon($icon) {
		return "<span class=\"glyphicon glyphicon-".$icon."\"></span> ";
	}

	public function badge($num, $pull_right = false) {
		return "<span class=\"badge".($pull_right? " pull-right\"" : "\"").">".$num."</span>";
	}

	public function alert_message_basic($class="info",$text="",$title="",$close=true) {
		$return = "<div class=\"alert alert-".$class.($close? " fade in" : "")."\">";
		if($close) $return .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</div>";
		return $return;
	}	

	public function alert_message_block($class="info",$text="",$title="",$close=false) {
		$return = "<div class=\"alert-block alert-".$class.($close? " fade in" : "")."\">";
		if($close) $return .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</div>";
		return $return;
	}

	public function list_group($items) {
		if(function($items) { foreach($items as $item) if(array_key_exists("url",$item)) return true; }) $div = true;		
		$return = "<".($div? "div" : "ul")." class=\"list-group\">";
		
		foreach($items as $item) {
			$e = array_key_exists("url", $item)? "a" : ($div? "span" : "li");
			$h = array_key_exists("url", $item)? " href=\"".$item["url"]."\"" : "";
			$i = array_key_exists("icon", $item)? self::glyphicon($item["icon"]) : "";
			$b = array_key_exists("badge", $item)? self::badge($item["badge"]) : "";
			$a = array_key_exists("active", $item)? " active" : "";
			$return .= "<".$e.$h." class=\"list-group-item".$a."\">".$i.$b.$item["text"]."</".$e.">";
		}
		$return .= "</".($div? "div" : "ul").">";
		return $return;
	}
}
?>