<?php
class Bootstrap {
	public static function icon($icon) { return self::glyphicon($icon); }

	// Please use fontawesome instead
	public static function glyphicon($icon, $extra='') {
		// Conversion array for legacy purposes.
		$conversion = array(
			// glyphicon => fontawesome
			"music" => "music",
			"th-list" => "th-list",
			"th" => "th",
			"cog" => "cog",
			"headphones" => "headphones",
			"list" => "list-ul",
			"info-sign" => "info",
			"chevron-right" => "chevron-circle-right",
			"home" => "home",
			"book" => "book",
			"user" => "user",
			"indent-left" => "users",
			"globe" => "globe",
			"edit" => "edit",
			"eye-open" => "eye",
			"plus-sign" => "plus-circle",
			"search" => "search",
			"question-sign" => "question-circle",
			"exclamation-sign" => "exclamation-circle",
			"upload" => "arrow-alt-circle-up",
			"gbp" => "pound-sign",
			"inbox" => "inbox",
			"flash" => "bolt",
			"list-alt" => "list-alt",
			"plus" => "plus-circle"
		);

		if(array_key_exists($icon, $conversion))
			return self::fontawesome($conversion[$icon], $extra);
		else
			return "<span class=\"glyphicon glyphicon-".$icon."\"></span> ";
	}

	public static function fontawesome($icon, $extra='') {
		return "<span class=\"fas fa-" . $icon . " " . $extra . "\"></span> ";
	}

	public static function badge($num, $pull_right = false) {
		return "<span class=\"badge".($pull_right? " pull-right\"" : "\"").">".$num."</span>";
	}

	public static function alert($class="info",$text="",$title="",$close=true) { 
		return self::alert_message_basic($class,$text,$title,$close);
	}

	public static function alert_message_basic($class="info",$text="",$title="",$close=true) {
		$return = "<div class=\"alert alert-".$class.($close? " fade in" : "")."\">";
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
			$i = array_key_exists("icon", $item)? self::glyphicon($item["icon"], "fa-lg fa-fw fa-pull-left") : "";
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

	public static function panel($class="info", $content, $header = NULL, $footer = NULL) {
		$return = "
		<div class=\"panel panel-".$class."\">
		  ".(!is_null($header)? "<div class=\"panel-heading\">
		    <h3 class=\"panel-title\">".$header."</h3>
		  </div>" : "")."
		  <div class=\"panel-body\">
		    ".$content."
		  </div>
		  ".(!is_null($footer)? "<div class=\"panel-footer\">
		  	  ".$footer."
		  </div>" : "")."
		</div>";
		return $return;
	}

}
?>