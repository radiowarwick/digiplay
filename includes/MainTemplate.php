<?php
class MainTemplate implements Template{
	protected static $menu;
	protected static $subtitle;
	protected static $masthead;
	protected static $summary;
	public static function get_menu(){
		if(!isset(self::$menu)) self::$menu = new Menu;
		return self::$menu;
	}
	public static function set_subtitle($subtitle){
		self::$subtitle = $subtitle;
	}
	public static function set_masthead($masthead) {
		self::$masthead = $masthead;
	}
	public static function set_summary($summary) {
		self::$summary = $summary;
	}
	public static function print_page($content){
		$main_menu = new Menu;
		$main_menu->add_many(
			array("index","Overview"),
			array("music","Music Library"),
			array("playlists","Playlists"),
			array("audiowalls","Audiowalls"),
			array("files","File Manager"),
			array("showplans","Show Planning"));
	if(Session::is_admin()) $main_menu->add("admin","Admin");
	
	$site_path_array = explode("/",SITE_PAGE);
	$main_menu->set_active($site_path_array[0]);
	
	$return = "<!DOCTYPE html> 
<html> 
	<head> 
		<title>RaW Digiplay";
	if(Output::get_title() != 'Untitled Page')
		$return .= " - ".Output::get_title();
	$return .= "</title> 
		<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/jquery-1.7.1.min.js\"></script>
		<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/bootstrap-modal.js\"></script>
		<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/bootstrap-buttons.js\"></script>
		<link rel=\"stylesheet/less\" href=\"".SITE_LINK_REL."lib/bootstrap.less\" />
		<link rel=\"stylesheet\" type=\"text/css\" href=\"".SITE_LINK_REL."css/dps.css\" />
		<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/less-1.1.5.min.js\"></script>
		<script type=\"text/javascript\">
			$(function () {
				
			})
		</script>";
	if(count(Output::get_stylesheets())>0)
		foreach(Output::get_stylesheets() AS $src){
			$return .= "\n	<link href=\"".$src."\" rel=\"stylesheet\" type=\"text/css\"/>";
		}
	if(count(Output::get_scripts())>0)
		foreach(Output::get_scripts() AS $src){
			$return .= "\n	<script src=\"".$src."\" type=\"text/javascript\"></script>";
		}
	
	if(count(Output::get_feeds())>0)
		foreach(Output::get_feeds() AS $feed){
			$return .= "\n	<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$feed['title']."\" href=\"".$feed['url']."\" />";
		}
	
	$return .= "
</head> 
	<body> 
		<div id=\"topbar\"> 
		<div class=\"topbar\">
			<div class=\"topbar-inner\">
				<div class=\"container\">
					<a class=\"brand\" href=\"".SITE_LINK_REL."\">Digiplay</a>
					".$main_menu->output(SITE_LINK_REL,3,"nav")."
					";
					if(Session::is_user()) { $return .= "
					<form class=\"pull-right\" action=\"\">
            			<input type=\"text\" placeholder=\"Search Tracks\">
          			</form>"; }
          			$return .= "
				</div>
			</div>
		</div>
		";

	if(isset(self::$masthead)) {
		$return .= "
		<div class=\"masthead\">
			<div class=\"intro\">
				<div class=\"container\">
					".self::$masthead."
				</div>
			</div>
		</div>";
	}

	if(isset(self::$summary)) {
		$return .= "
		<div class=\"summary\">
			<div class=\"container\">
				".self::$summary."
			</div>
		</div>";
	}

	$return .= "
		<div class=\"container\">";

	if(Output::get_title() != 'Untitled Page') {
		$return .= "
			<div class=\"page-header\">
				<h1>".Output::get_title();
				if(isset(self::$subtitle)) {
					$return .= " <small>".self::$subtitle."</small>";
				}
		$return .= "</h1>
			</div>";
	}

	$return .= "
			<div class=\"row\">";
	if (isset(self::$menu) && self::$menu->size()>0){
		$return .= "
				<div class=\"span4\">
					<div class=\"well\">".
					self::$menu->output(SITE_LINK_REL.current(explode("/",SITE_PATH))."/")."
					</div>
				</div>
				<div class=\"span12\">";
	} else {
		$return .= "
				<div class=\"span16\">";
	}
	
	$return .= $content;

	$return .= "
				</div>
			</div>
		</div>";

	if(Session::is_user())
		$return .= "
					<div class=\"modal hide fade\" id=\"logout-modal\">
					<div class=\"modal-header\">
						<a class=\"close\" href=\"#\">&times;</a>
						<h3>Log out?</h3>
					</div>
					<div class=\"modal-body\">
						You'll lose any unsaved changes on this page.
					</div>
					<div class=\"modal-footer\">
						<a class=\"btn primary\" href=\"".SITE_LINK_REL."ajax/logout\">Yes, log out</a>
					</div>";

	$return .= "
			</div>
		<footer class=\"footer\"> 
			<div class=\"container\">
				<p class=\"pull-right\">
					<a href=\"".SITE_LINK_REL."\"><img src=\"".SITE_LINK_REL."images/template/footer_logo.png\" src=\"RaW 1251AM\" /></a> 
				</p>
				<p>";
	if(Session::is_user()) $return .= "Logged in as ".Session::get_username().". <a href=\"".SITE_LINK_REL."ajax/logout\" data-controls-modal=\"logout-modal\" data-backdrop=\"true\" data-keyboard=\"true\">Logout</a><br />";
	else $return .= "Not logged in<br />";				
	$return .= "Copyright &copy; 2011 Radio Warwick
				</p>
			</div>
		</footer>
	</body> 
</html>";
	return $return;
	}

	public static function print_http_error($error){
		switch($error){
			case 401: return "<h1>Unauthorized</h1>\n<p>This page requires special permissions</p>"; break;
			case 404: return "<h1>Page not found</h1>\n<p>This page does not exist</p>"; break;
			case 410: return "<h1>This page has left us</h1>\n<p>We are sad.</p>"; break;
		}
	}

}

?>
