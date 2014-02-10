<?php
class AudiowallStyles {
	public function get($id) {
		return self::get_by_id($id);
	}
	
	public function get_by_id($id) {
		return DigiplayDB::select("aw_styles.id, aw_styles.name, aw_styles.description, aw_styles_props.value AS background FROM aw_styles, aw_styles_props WHERE aw_styles.id = :id AND aw_styles_props.style_id = :id AND aw_styles_props.prop_id = 2;", "AudiowallStyle", false, array(":id" => $id));
	}
	
	public function get_all() {
		return DigiplayDB::select("aw_styles.id, aw_styles.name, aw_styles.description, aw_styles_props.value AS background FROM aw_styles, aw_styles_props WHERE aw_styles_props.style_id = aw_styles.id AND aw_styles_props.prop_id = 2;", "AudiowallStyle", true);
	}
}
?>