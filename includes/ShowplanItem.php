<?php

class ShowplanItem {
	protected $id;
	protected $showplanid;
	protected $position;
	protected $title;
	protected $audioid;
	protected $scriptid;
	protected $comment;
	protected $length;

	public function get_id() { return $this->id; }
	public function get_showplan() { return Showplans::get_by_id($showplanid); }
	public function get_position() { return $this->position; }
	public function get_title() { return $this->title; }
	public function get_audio() { return Audio::get_by_id($this->audioid); }
	public function get_script() { return Scripts::get_by_id($this->scriptid); }
	public function get_comment() { return $this->comment; }
	public function get_length() { return $this->length; }

	public function set_showplan($showplan) { $this->showplanid = $showplan->get_id(); }
	public function set_position($position) { $this->position = $position; }
	public function set_title($title) { $this->title = $title; }
	public function set_audio($audio) { $this->audioid = $audio->get_id(); }
	public function set_script($script) { $this->scriptid = $script->get_id(); }
	public function set_comment($comment) { $this->comment = $comment; }
	public function set_length($length) { $this->length = $length; }

	public function save() {
		if($this->id) DigiplayDB::query("UPDATE showitems SET showplanid = ".$this->showplanid.", position = ".$this->position.", title = '".pg_escape_string($this->title)."', audioid = ".$this->audioid.", scriptid = ".$this->scriptid.", comment = '".pg_escape_string($this->comment)."', length = ".$this->length." WHERE id = ".$this->id.";");
		else {
			$return = pg_fetch_array(DigiplayDB::query("INSERT INTO showitems (showplanid,position,title,audioid,scriptid,comment,length) VALUES (".$this->showplanid.", ".$this->position.", '".pg_escape_string($this->title)."',".($this->audioid? $this->audioid : "NULL").",".($this->scriptid? $this->scriptid : "NULL").",'".pg_escape_string($this->comment)."', ".($this->length? $this->length : 0).") RETURNING id;"));
			$this->id = $return["id"];
		}
		return $this->id;
	}
}

?>