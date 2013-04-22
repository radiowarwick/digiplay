<?php
class MainTemplate implements Template{
	protected static $sidebar;
	protected static $menu;
	protected static $subtitle;
	protected static $feature_image;
	protected static $feature_html;
	public static function set_subtitle($subtitle){
		self::$subtitle = $subtitle;
	}
	public static function set_feature_image($image) {
		self::$feature_image = $image;
	}
	public static function set_feature_html($html) {
		self::$feature_html = $html;
	}
	public static function set_sidebar($sidebar) {
		self::$sidebar = $sidebar;
	}
	public static function set_menu($menu) {
		self::$menu = $menu;
	}
	public static function print_page($content){
		if(strlen(SITE_PATH)>0){
			$sitePathArray = explode("/",SITE_PATH);
			for($i = 0; $i < count($sitePathArray); $i++){
				$file = SITE_FILE_PATH.implode("/",array_slice($sitePathArray,0,$i+1))."/sidebar.php";
				if(file_exists($file)){
					include($file);
					MainTemplate::set_sidebar(sidebar());
					MainTemplate::set_menu(menu());
				}
			}
			unset($sitePathArray,$i,$file);
		}

		$main_menu = new Menu;
		$main_menu->add_many(
			array("music","Music Library","music"),
			array("playlists","Playlists","th-list"),
			array("audiowalls","Audiowalls","th"),
			array("files","Files","folder-open"),
			array("showplans","Show Planning","tasks"));
	if(Session::is_admin()) $main_menu->add("admin","Admin","cog");
	
	$site_path_array = explode("/",SITE_PAGE);
	$main_menu->set_active($site_path_array[0]);
	
	header("Content-Type: text/html; charset=utf-8");
	$return = "<!DOCTYPE html> 
<html> 
	<head> 
		<title>RaW Digiplay";
	if(Output::get_title() != 'Untitled Page')
		$return .= " - ".Output::get_title();
	$return .= "</title> 
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		<script type=\"text/javascript\" src=\"//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js\"></script>
		";
		if(Session::is_developer()) {
			$return .= "<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/bootstrap.js\"></script>
		<link rel=\"stylesheet\" href=\"".SITE_LINK_REL."css/bootstrap.css\">
		";
		} else {
			$return .= "<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/bootstrap.min.js\"></script>
		<link rel=\"stylesheet\" href=\"".SITE_LINK_REL."css/bootstrap.min.css\">
		";
		}
	if(count(Output::get_less_stylesheets())>0) {
		foreach(Output::get_less_stylesheets() AS $src){
			$return .= "<link href=\"".$src."\" rel=\"stylesheet/less\">
		";
		}
		$return .= "<script type=\"text/javascript\" src=\"".SITE_LINK_REL."js/less-1.1.5.min.js\"></script>
		";
	}

	if(count(Output::get_stylesheets())>0)
		foreach(Output::get_stylesheets() AS $src){
			$return .= "<link href=\"".$src."\" rel=\"stylesheet\" type=\"text/css\">
		";
		}
	if(count(Output::get_scripts())>0)
		foreach(Output::get_scripts() AS $src){
			$return .= "<script src=\"".$src."\" type=\"text/javascript\"></script>
		";
		}
	if(count(Output::get_feeds())>0)
		foreach(Output::get_feeds() AS $feed){
			$return .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$feed['title']."\" href=\"".$feed['url']."\">
		";
		}
	$return .= "
		<link rel=\"stylesheet\" href=\"".SITE_LINK_REL."css/style.css\">
		<script src=\"".SITE_LINK_REL."js/main.js\" type=\"text/javascript\"></script>
	</head>
	<body>
		<div id=\"wrap\">
			".(isset(self::$feature_image)? "<div class=\"feature-image\" style=\"background-image: url('".self::$feature_image."')\"></div>" : "")."
			<div class=\"navbar navbar-inverse navbar-fixed-top\">
				<div class=\"container\">
					<a class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".nav-collapse\">
	         			<span class=\"icon-bar\"></span>
	          			<span class=\"icon-bar\"></span>
	         			<span class=\"icon-bar\"></span>
	        		</a>
					<a class=\"navbar-brand hidden-tablet\" href=\"".SITE_LINK_REL."\">Digiplay</a>
					<div class=\"nav-collapse collapse\">"
						.$main_menu->output(SITE_LINK_REL,6,"nav");
						if(Session::is_user()) { $return .= "
						<ul class=\"nav pull-right hidden-tablet\">
							<li>
								<form class=\"navbar-form pull-right\" action=\"".SITE_LINK_REL."music/search\" method=\"GET\">
	            					<input type=\"text\" class=\"search-query\" style=\"width: 180px\" placeholder=\"Search Tracks\" name=\"q\" autocomplete=\"off\">
	            				</form>
	            			</li>
	            			<li>
		          				<ul id=\"quick-search\" class=\"dropdown-menu pull-right\"></ul>
		          			</li>
		          		</ul>
		          		"; }
	          			$return .= "
					</div>
				</div>
			</div>";
	$return .= "
			<div class=\"container\">
			".(isset(self::$feature_html)? "<div class=\"feature\">".self::$feature_html."</div>" : "")."";
	if(Output::get_title() != 'Untitled Page') {
		$return .= "
				<div class=\"page-header\">
					<h2>".Output::get_title();
					if(isset(self::$subtitle)) {
						$return .= " <small>".self::$subtitle."</small>";
					}
		$return .= "</h2>
				</div>";
	}

	$return .= "
				<div class=\"row\">";
	if (isset(self::$sidebar) || isset(self::$menu)){
		$return .= "
				<div class=\"col-span-3\">";
		if(isset(self::$menu)) {
			$return .= "	
					<div class=\"well sidebar-menu\">".
					self::$menu."
					</div>";
		}
		if(isset(self::$sidebar)) {
			$return .= "	
					<div class=\"sidebar hidden-phone\">".
					self::$sidebar."
					</div>";
		}
		$return .= "
					</div>
					<div class=\"col-span-9\">";
	} else {
		$return .= "
				<div class=\"col-span-12\">";
	}

	$return .= $content;

	$return .= "
					</div>
				</div>
			</div>";

	if(Session::is_user())
		$return .= "
		<div class=\"modal fade\" id=\"logout-modal\">
			<div class=\"modal-dialog\">
				<div class=\"modal-content\">
					<div class=\"modal-header\">
						<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
						<h4 class=\"modal-title\">Log out?</h4>
					</div>
					<div class=\"modal-body\">
						You'll lose any unsaved changes on this page.
					</div>
					<div class=\"modal-footer\">
						<a class=\"btn btn-primary\" href=\"".SITE_LINK_REL."ajax/logout.php\">Yes, log out</a>
					</div>
				</div>
			</div>
		</div>";

	$return .= "
		<div id=\"push\"></div>
	</div>
		<footer>
			<div class=\"container\">
				<div class=\"row\">
					<div class=\"col-span-8\">
						<p class=\"text-muted credit\">";
	if(Session::is_user()) $return .= "Logged in as ".Session::get_username().". <a href=\"#logout-modal\" data-toggle=\"modal\">Logout</a>. ";
	else $return .= "Not logged in. ";
	$return .= "Copyright &copy; 2011-".date("y")." Radio Warwick
						</p>
					</div>
					<div class=\"col-span-4\">
						<a href=\"".SITE_LINK_REL."\"><img src=\"".SITE_LINK_REL."img/footer_logo.png\" alt=\"RaW 1251AM\" class=\"pull-right\"/></a>
					</div>
				</div>
			</div>
		</footer>
	</body> 
</html>";
	return $return;
	}

	public static function print_http_error($error){
		switch($error){
			case 401: return "<h2>Unauthorized</h2>\n<p>This page requires special permissions</p>"; break;
			case 404: return "<h2>Page not found</h2>\n<p>This page does not exist</p>"; break;
			case 410: return "<h2>This page has left us</h2>\n<p>We are sad.</p>"; break;
		}
	}

}

?>
