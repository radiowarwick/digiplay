<?php
class MainTemplate{
	protected static $sidebar;
	protected static $menu;
	protected static $subtitle;
	protected static $feature_image;
	protected static $feature_html;
	protected static $barebones = false;

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
	public static function set_barebones($barebones) {
		self::$barebones = $barebones;
	}
	public static function print_page($content){
		if(strlen(LINK_PATH)>0){
			$sitePathArray = explode("/",LINK_PATH);
			for($i = 0; $i < count($sitePathArray); $i++){
				$file = FILE_ROOT.implode("/",array_slice($sitePathArray,0,$i+1))."/sidebar.php";
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
	
	$site_path_array = explode("/",LINK_PATH);
	$main_menu->set_active($site_path_array[0]);
	
	header("Content-Type: text/html; charset=utf-8");
	$return = "<!DOCTYPE html> 
<html> 
	<head> 
		<title>RaW Digiplay";
	if(Output::get_title() != 'Untitled Page') $return .= " - ".Output::get_title();

	$return .= "</title> 
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		<script type=\"text/javascript\" src=\"//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js\"></script>
		<script type=\"text/javascript\" src=\"".LINK_ABS."js/bootstrap.min.js\"></script>
		";
	if(isset($_REQUEST["theme"])) $return .= "<link rel=\"stylesheet\" href=\"//netdna.bootstrapcdn.com/bootswatch/3.0.0/".$_REQUEST["theme"]."/bootstrap.min.css\">\n";
	else $return .= "<link rel=\"stylesheet\" href=\"".LINK_ABS."css/bootstrap.min.css\">\n";

	if(count(Output::get_stylesheets())>0) foreach(Output::get_stylesheets() AS $src) $return .= "<link href=\"".$src."\" rel=\"stylesheet\" type=\"text/css\">\n";
	if(count(Output::get_scripts())>0) foreach(Output::get_scripts() AS $src) $return .= "<script src=\"".$src."\" type=\"text/javascript\"></script>\n";
	if(count(Output::get_feeds())>0) foreach(Output::get_feeds() AS $feed) $return .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".$feed['title']."\" href=\"".$feed['url']."\">\n";

	if(self::$barebones == false) {
		$return .= "
			<link rel=\"stylesheet\" href=\"".LINK_ABS."css/style.css\">
			<script src=\"".LINK_ABS."js/main.js\" type=\"text/javascript\"></script>
			";
	}

	$return .= "	</head>
	<body>";
	if(self::$barebones == false) {
		$return .= "
		<div id=\"wrap\">
			<nav class=\"navbar navbar-inverse navbar-fixed-top\" role=\"navigation\">
				<div class=\"container\">
				<div class=\"navbar-header\">
					<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-dps-collapse\">
      					<span class=\"sr-only\">Toggle navigation</span>
      					<span class=\"icon-bar\"></span>
      					<span class=\"icon-bar\"></span>
      					<span class=\"icon-bar\"></span>
    				</button>
    				<a class=\"navbar-brand hidden-sm\" href=\"".LINK_ABS."\">Digiplay</a>
				</div>
				<div class=\"navbar-collapse collapse navbar-dps-collapse\">"
					.$main_menu->output(LINK_ABS,6,"nav navbar-nav");
					if(Session::is_user()) { $return .= "
					<ul class=\"nav search-pull-right hidden-sm\">
						<li>
							<form class=\"navbar-form\" action=\"".LINK_ABS."music/search\" method=\"GET\" role=\"search\">
								<div class=\"form-group\">
	            					<input type=\"text\" class=\"form-control search-query\" placeholder=\"Search Tracks\" name=\"q\" autocomplete=\"off\">
	            				</div>
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
			</nav>
			".(isset(self::$feature_html)? "<div class=\"jumbotron".(isset(self::$feature_image)? " feature-image\" style=\"background-image: url('".self::$feature_image."')\"" : "\"")."><div class=\"container\">".self::$feature_html."</div></div>" : "").
			"<div class=\"container\">";
		
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
				<div class=\"col-md-3\">";
			if(isset(self::$menu)) $return .= self::$menu;
			if(isset(self::$sidebar)) {
				$return .= "	
					<div class=\"panel panel-noborder visible-md visible-lg\">
						<div class=\"panel-body\">".
						self::$sidebar."
						</div>
					</div>";
			}
			$return .= "
				</div>
				<div class=\"col-md-9\">";
		} else {
			$return .= "
				<div class=\"col-md-12\">";
		}
	}

	$return .= $content;

	if(self::$barebones == false) {
		$return .= "
					</div>
				</div>
			</div>";

		if(Session::is_user()) $return .= Bootstrap::modal("logout-modal", "You'll lose any unsaved changes on this page.", "Log out?", "<a class=\"btn btn-primary\" href=\"".LINK_ABS."ajax/logout.php\">Yes, log out</a>");

		$return .= "
		<div id=\"push\"></div>
	</div>
		<footer class=\"jumbotron\">
			<div class=\"container\">
				<div class=\"row\">
					<div class=\"col-sm-8\">
						<p class=\"text-muted credit\">";
		if(Session::is_user()) $return .= "Logged in as ".Session::get_username().". <a href=\"#logout-modal\" data-toggle=\"modal\">Logout</a>. ";
		else $return .= "Not logged in. ";
		$return .= "Copyright &copy; 2011-".date("y")." Radio Warwick
						</p>
					</div>
					<div class=\"col-sm-4\">
						<a href=\"".LINK_ABS."\"><img src=\"".LINK_ABS."img/footer_logo.png\" alt=\"RaW 1251AM\" class=\"pull-right\"/></a>
					</div>
				</div>
			</div>
		</footer>";
	}

	$return .= "
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
