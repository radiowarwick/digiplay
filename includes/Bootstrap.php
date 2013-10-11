<?php
class Bootstrap {
	public static function glyphicon($icon) {
		return "<span class=\"glyphicon glyphicon-".$icon."\"></span> ";
	}

	public static function badge($num, $pull_right = false) {
		return "<span class=\"badge".($pull_right? " pull-right\"" : "\"").">".$num."</span>";
	}

	public static function alert_message_basic($class="info",$text="",$title="",$close=true) {
		$return = "<div class=\"alert alert-".$class.($close? " fade in" : "")."\">";
		if($close) $return .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</div>";
		return $return;
	}	

	public static function alert_message_block($class="info",$text="",$title="",$close=false) {
		$return = "<div class=\"alert-block alert-".$class.($close? " fade in" : "")."\">";
		if($close) $return .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>";
		if($title) $return .= "<strong>".$title."</strong> ";
		$return .= $text;
		$return .= "</div>";
		return $return;
	}

	public static function list_group($items) {
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

	public static function modal($id, $content, $header = NULL, $footer = NULL) {
		$return = "
		<div class=\"modal fade\" id=\"".$id."\">
			<div class=\"modal-dialog\">
				<div class=\"modal-content\">
					".(!is_null($header)? "<div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
						<h4 class=\"modal-title\">".$header."</h4>
					</div>" : "")."
					<div class=\"modal-body\">
						".$content."
					</div>
					".(!is_null($footer)? "<div class=\"modal-footer\">
						".$footer."
					</div>" : "")."
				</div>
			</div>
		</div>";
		return $return;
	}
}
?>