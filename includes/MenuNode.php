<?php
class MenuNode{
	private $url;
	private $label;
	private $menu;
	private $active;
	
	public function __construct($url,$label){
		$this->url		= $url;
		$this->label	= $label;
	}
	public function new_menu(){
		$this->menu = new Menu;
		return $this->menu;
	}
	public function set_active(){
		$this->active = true;
	}
	public function output($path,$tabs){
		$out = "\n".str_repeat("\t",$tabs);
		$out .= "<li".($this->active?" class=\"active\"":"").">";
		$out .= "<a href=\"".$this->url($path)."\">".$this->label."</a>";
		if(isset($this->menu))	$out .= $this->menu->output($path.$this->url."/",$tabs+1);
		$out  .= "</li>";
		return $out;
	}
	private function url($path){
		if(substr($this->url,0,7) == "http://")
			return $this->url;
		else
			return $path.$this->url;
	}
}
?>
