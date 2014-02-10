<?php
class AudiowallStyle {
	protected $id;
	protected $name;
	protected $description;
	protected $background;
	protected $foreground = 0;
	
	public function get_id(){ return $this->id; }
	public function get_name(){ return $this->name; }
	public function get_description(){ return $this->description; }
	public function get_background(){ return $this->background; }
	public function get_foreground(){ return $this->foreground; }
	
	/* Extended functions */
	public function get_foreground_rgb(){
		$r = round( ( $this->foreground >> 16 ) & 255 );
		$g = round( ( $this->foreground >> 8 ) & 255 );
		$b = round( $this->foreground & 255 );
		return "rgb(".$r.",".$g.",".$b.")";
	}
	public function get_background_rgb(){
		$r = round( ( $this->background >> 16 ) & 255 );
		$g = round( ( $this->background >> 8 ) & 255 );
		$b = round( $this->background & 255 );
		return "rgb(".$r.",".$g.",".$b.")";
	}	

}
?>