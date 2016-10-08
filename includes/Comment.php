<?php
Class Comment {
	protected $id;
	protected $author;
	protected $comment;
	protected $postdate;
	protected $faultid;

	public function get_id() { return $this->id; }
	public function get_author() { return $this->author; }
	public function get_comment() { return $this->comment; }
	public function get_postdate() { return date('jS F Y, g:ia', $this->postdate); }
	public function get_faultid() { return $this->faultid; }

	public function get_real_author($id) {
		$user = Users::get_by_id($id);
		return $user->get_display_name();
	}

	public function set_author($author) { $this->author = $author; }
	public function set_comment($comment) { $this->comment = $comment; }
	public function set_postdate($postdate) { $this->postdate = $postdate; }
	public function set_faultid($faultid) { $this->faultid = $faultid; }

	public function save() {
		if(!$this->comment) return false;
		if($this->id) DigiplayDB::update("info_fault_comments", get_object_vars($this), "id = ".$this->id);
		else $this->id = DigiplayDB::insert("info_fault_comments", get_object_vars($this), "id");
		return $this->id;
	}

}
?>
